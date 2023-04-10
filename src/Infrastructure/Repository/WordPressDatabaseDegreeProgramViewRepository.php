<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\AdmissionRequirementsTranslated;
use Fau\DegreeProgram\Common\Application\ConditionalFieldsFilter;
use Fau\DegreeProgram\Common\Application\ContentTranslated;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\DegreeTranslated;
use Fau\DegreeProgram\Common\Application\ImageView;
use Fau\DegreeProgram\Common\Application\Link;
use Fau\DegreeProgram\Common\Application\Links;
use Fau\DegreeProgram\Common\Application\RelatedDegreeProgram;
use Fau\DegreeProgram\Common\Application\RelatedDegreePrograms;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\DegreeProgramRepository;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\FacultyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Sanitizer\HtmlDegreeProgramSanitizer;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use RuntimeException;
use WP_Post;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class WordPressDatabaseDegreeProgramViewRepository implements DegreeProgramViewRepository
{
    public function __construct(
        private DegreeProgramRepository $degreeProgramRepository,
        private HtmlDegreeProgramSanitizer $htmlContentSanitizer,
        private ConditionalFieldsFilter $conditionalFieldsFilter,
    ) {
    }

    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw
    {
        try {
            $degreeProgram = $this->degreeProgramRepository->getById($degreeProgramId);
            return DegreeProgramViewRaw::fromDegreeProgram($degreeProgram);
        } catch (RuntimeException) {
            return null;
        }
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function findTranslated(
        DegreeProgramId $degreeProgramId,
        string $languageCode
    ): ?DegreeProgramViewTranslated {

        $raw = $this->findRaw($degreeProgramId);
        if (!$raw instanceof DegreeProgramViewRaw) {
            return null;
        }

        $raw = $this->conditionalFieldsFilter->filter(
            $raw,
            $this->findFacultySlugs($raw),
        );

        $main = $this->translateDegreeProgram($raw, $languageCode);
        foreach (MultilingualString::LANGUAGES as $code => $name) {
            if ($code === $languageCode) {
                continue;
            }

            $main = $main->withTranslation(
                $this->translateDegreeProgram($raw, $code),
                $code
            );
        }

        return $main;
    }

    private function findFacultySlugs(DegreeProgramViewRaw $raw): ArrayOfStrings
    {
        $terms = get_the_terms(
            $raw->id()->asInt(),
            FacultyTaxonomy::KEY
        );

        if (!is_array($terms)) {
            return ArrayOfStrings::new();
        }

        /** @var array<string> $slugs */
        $slugs = wp_list_pluck($terms, 'slug');

        return ArrayOfStrings::new(...$slugs);
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    private function translateDegreeProgram(
        DegreeProgramViewRaw $raw,
        string $languageCode
    ): DegreeProgramViewTranslated {

        $title = $raw->title()->asString($languageCode);

        return new DegreeProgramViewTranslated(
            id: $raw->id(),
            link: $this->link(
                $raw->id()->asInt(),
                $raw->slug(),
                $languageCode,
            ),
            slug: $raw->slug()->asString($languageCode),
            lang: $languageCode,
            featuredImage: $this->imageView(
                $raw->featuredImage(),
                DegreeProgram::FEATURED_IMAGE,
                $title
            ),
            teaserImage: $this->imageView(
                $raw->teaserImage(),
                DegreeProgram::TEASER_IMAGE,
                $title
            ),
            title: $raw->title()->asString($languageCode),
            subtitle: $raw->subtitle()->asString($languageCode),
            standardDuration: $raw->standardDuration(),
            feeRequired: $raw->isFeeRequired(),
            start: $raw->start()->asArrayOfStrings($languageCode),
            numberOfStudents: $raw->numberOfStudents()->asString(),
            teachingLanguage: $raw->teachingLanguage()->asString($languageCode),
            attributes: $raw->attributes()->asArrayOfStrings($languageCode),
            degree: DegreeTranslated::fromDegree($raw->degree(), $languageCode),
            faculty: Links::fromMultilingualLinks($raw->faculty(), $languageCode),
            location: $raw->location()->asArrayOfStrings($languageCode),
            subjectGroups: $raw->subjectGroups()->asArrayOfStrings($languageCode),
            videos: $raw->videos(),
            metaDescription: $raw->metaDescription()->asString($languageCode),
            content: ContentTranslated::fromContent($raw->content(), $languageCode)
                ->mapDescriptions([$this, 'formatContentField']),
            admissionRequirements: AdmissionRequirementsTranslated::fromAdmissionRequirements(
                $raw->admissionRequirements(),
                $languageCode
            ),
            contentRelatedMasterRequirements: $this->formatContentField(
                $raw->contentRelatedMasterRequirements()->asString($languageCode)
            ),
            applicationDeadlineWinterSemester: $this->formatContentField(
                $raw->applicationDeadlineWinterSemester()
            ),
            applicationDeadlineSummerSemester:  $this->formatContentField(
                $raw->applicationDeadlineSummerSemester()
            ),
            detailsAndNotes: $this->formatContentField(
                $raw->detailsAndNotes()->asString($languageCode)
            ),
            languageSkills: $this->formatContentField(
                $raw->languageSkills()->asString($languageCode)
            ),
            languageSkillsHumanitiesFaculty: $this->formatContentField(
                $raw->languageSkillsHumanitiesFaculty()
            ),
            germanLanguageSkillsForInternationalStudents: Link::fromMultilingualLink(
                $raw->germanLanguageSkillsForInternationalStudents(),
                $languageCode
            ),
            startOfSemester: Link::fromMultilingualLink($raw->startOfSemester(), $languageCode),
            semesterDates: Link::fromMultilingualLink($raw->semesterDates(), $languageCode),
            examinationsOffice: Link::fromMultilingualLink($raw->examinationsOffice(), $languageCode),
            examinationRegulations: $raw->examinationRegulations(),
            moduleHandbook: $raw->moduleHandbook(),
            url: $raw->url()->asString($languageCode),
            department: $raw->department()->asString($languageCode),
            studentAdvice: Link::fromMultilingualLink($raw->studentAdvice(), $languageCode),
            subjectSpecificAdvice: Link::fromMultilingualLink($raw->subjectSpecificAdvice(), $languageCode),
            serviceCenters: Link::fromMultilingualLink($raw->serviceCenters(), $languageCode),
            studentRepresentatives: $raw->studentRepresentatives(),
            semesterFee: Link::fromMultilingualLink($raw->semesterFee(), $languageCode),
            degreeProgramFees: $raw->degreeProgramFees()->asString($languageCode),
            abroadOpportunities: Link::fromMultilingualLink($raw->abroadOpportunities(), $languageCode),
            keywords: $raw->keywords()->asArrayOfStrings($languageCode),
            areaOfStudy: Links::fromMultilingualLinks($raw->areaOfStudy(), $languageCode),
            combinations: $this->relatedDegreePrograms($raw->combinations()->asArray(), $languageCode),
            limitedCombinations: $this->relatedDegreePrograms($raw->limitedCombinations()->asArray(), $languageCode),
            notesForInternationalApplicants: Link::fromMultilingualLink($raw->notesForInternationalApplicants(), $languageCode),
            applyNowLink: Link::fromMultilingualLink($raw->applyNowLink(), $languageCode),
            entryText: $this->formatContentField($raw->entryText()->asString($languageCode)),
        );
    }

    /**
     * @psalm-param 'featured_image' | 'teaser_image' $type
     */
    private function imageView(Image $image, string $type, string $alt): ImageView
    {
        if (!$image->id()) {
            return ImageView::empty();
        }

        return ImageView::new(
            $image->id(),
            $image->url(),
            wp_get_attachment_image(
                $image->id(),
                (string) apply_filters(
                    'fau.image_size',
                    'full',
                    $type,
                ),
                false,
                [
                    'alt' => $alt,
                ]
            )
        );
    }

    private function link(int $id, MultilingualString $slug, string $languageCode): string
    {
        $permalink = get_the_permalink($id);
        if (!$permalink) {
            return '';
        }

        if ($languageCode === MultilingualString::DE) {
            return $permalink;
        }

        return str_replace($slug->inGerman(), $slug->inEnglish(), $permalink);
    }

    public function formatContentField(string $content): string
    {
        // Temporarily removes shortcodes generation to allow remove it with sanitizer
        remove_filter('the_content', 'do_shortcode', 11);
        $content = (string) apply_filters('the_content', $content);
        $content = $this->htmlContentSanitizer->sanitizeContentField($content);
        add_filter('the_content', 'do_shortcode', 11);

        return do_shortcode($content);
    }

    /**
     * @param array<int> $ids
     */
    private function relatedDegreePrograms(array $ids, string $languageCode): RelatedDegreePrograms
    {
        $result = [];
        foreach ($ids as $id) {
            $post = get_post($id);
            if (!$post instanceof WP_Post) {
                continue;
            }

            $result[] = $this->relatedDegreeProgram($post, $languageCode);
        }

        return RelatedDegreePrograms::new(...$result);
    }

    private function relatedDegreeProgram(WP_Post $post, string $languageCode): RelatedDegreeProgram
    {
        if ($languageCode === MultilingualString::DE) {
            return RelatedDegreeProgram::new(
                $post->ID,
                $post->post_title,
                (string) get_the_permalink($post),
            );
        }

        return RelatedDegreeProgram::new(
            $post->ID,
            (string) get_post_meta(
                $post->ID,
                BilingualRepository::addEnglishSuffix('title'),
                true
            ),
            home_url(
                (string) get_post_meta(
                    $post->ID,
                    BilingualRepository::addEnglishSuffix('post_name'),
                    true
                )
            ),
        );
    }
}
