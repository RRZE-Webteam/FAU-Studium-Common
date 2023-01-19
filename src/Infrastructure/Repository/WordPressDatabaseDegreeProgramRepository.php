<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\ContentTranslated;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\DegreeTranslated;
use Fau\DegreeProgram\Common\Application\Link;
use Fau\DegreeProgram\Common\Application\RelatedDegreeProgram;
use Fau\DegreeProgram\Common\Application\RelatedDegreePrograms;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\ContentItem;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\DegreeProgramIds;
use Fau\DegreeProgram\Common\Domain\DegreeProgramRepository;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AreaOfStudyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AttributeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\BachelorOrTeachingDegreeAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\DegreeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\ExaminationRegulationsTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\ExaminationsOfficeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\FacultyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\GermanLanguageSkillsForInternationalStudentsTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\KeywordTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\MasterDegreeAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\NumberOfStudentsTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SemesterTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\StudyLocationTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SubjectGroupTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SubjectSpecificAdviceTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TeachingLanguageTaxonomy;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use Fau\DegreeProgram\Common\LanguageExtension\IntegersListChangeset;
use RuntimeException;
use WP_Post;
use WP_Term;

final class WordPressDatabaseDegreeProgramRepository extends BilingualRepository implements DegreeProgramRepository, DegreeProgramViewRepository
{
    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     * phpcs:disable Generic.Metrics.CyclomaticComplexity.TooHigh
     */
    public function getById(DegreeProgramId $degreeProgramId): DegreeProgram
    {
        $postId = $degreeProgramId->asInt();
        $post = get_post($postId);

        if (!$post instanceof WP_Post || $post->post_type !== DegreeProgramPostType::KEY) {
            throw new RuntimeException('Could not find degree program with id ' . (string) $postId);
        }

        $featuredImageId = (int) get_post_thumbnail_id($post);
        $teaserImageId = (int) get_post_meta($postId, DegreeProgram::TEASER_IMAGE, true);

        /**
         * @var string[]|string $videos
         */
        $videos = get_post_meta($postId, DegreeProgram::VIDEOS, true);

        return new DegreeProgram(
            id: $degreeProgramId,
            featuredImage: Image::new(
                $featuredImageId,
                (string) wp_get_attachment_image_url($featuredImageId, 'full')
            ),
            teaserImage: Image::new(
                $teaserImageId,
                (string) wp_get_attachment_image_url($teaserImageId, 'full')
            ),
            title: MultilingualString::fromTranslations(
                'post:title',
                $post->post_title,
                (string)get_post_meta(
                    $postId,
                    BilingualRepository::addEnglishSuffix('title'),
                    true,
                ),
            ),
            subtitle: $this->bilingualPostMeta($post, DegreeProgram::SUBTITLE),
            standardDuration: (int)get_post_meta($postId, DegreeProgram::STANDARD_DURATION, true),
            feeRequired: (bool)get_post_meta($postId, DegreeProgram::FEE_REQUIRED, true),
            start: $this->bilingualTermsList($post, SemesterTaxonomy::KEY),
            numberOfStudents: $this->numberOfStudents($post),
            teachingLanguage: $this->bilingualTermName(
                $this->firstTerm($post, TeachingLanguageTaxonomy::KEY)
            ),
            attributes: $this->bilingualTermsList($post, AttributeTaxonomy::KEY),
            degree: $this->degree($post),
            faculty: $this->bilingualLinkFromTerm($this->firstTerm($post, FacultyTaxonomy::KEY)),
            location: $this->bilingualTermName($this->firstTerm($post, StudyLocationTaxonomy::KEY)),
            subjectGroups: $this->bilingualTermsList($post, SubjectGroupTaxonomy::KEY),
            videos: ArrayOfStrings::new(
                ...array_map(
                    'strval',
                    array_filter((array) $videos)
                )
            ),
            metaDescription: $this->bilingualPostMeta($post, DegreeProgram::META_DESCRIPTION),
            content: Content::new(
                about: $this->contentItem($post, Content::ABOUT),
                structure: $this->contentItem($post, Content::STRUCTURE),
                specializations: $this->contentItem($post, Content::SPECIALIZATIONS),
                qualitiesAndSkills: $this->contentItem($post, Content::QUALITIES_AND_SKILLS),
                whyShouldStudy: $this->contentItem($post, Content::WHY_SHOULD_STUDY),
                careerProspects: $this->contentItem($post, Content::CAREER_PROSPECTS),
                specialFeatures: $this->contentItem($post, Content::SPECIAL_FEATURES),
                testimonials: $this->contentItem($post, Content::TESTIMONIALS),
            ),
            admissionRequirements: AdmissionRequirements::new(
                bachelorOrTeachingDegree: $this->bilingualLinkFromTerm(
                    $this->firstTerm(
                        $post,
                        BachelorOrTeachingDegreeAdmissionRequirementTaxonomy::KEY
                    )
                ),
                teachingDegreeHigherSemester: $this->bilingualLinkFromTerm(
                    $this->firstTerm(
                        $post,
                        TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy::KEY
                    )
                ),
                master: $this->bilingualLinkFromTerm(
                    $this->firstTerm(
                        $post,
                        MasterDegreeAdmissionRequirementTaxonomy::KEY,
                    )
                ),
            ),
            contentRelatedMasterRequirements: $this->bilingualPostMeta(
                $post,
                DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS
            ),
            applicationDeadlineWinterSemester:
                (string)get_post_meta($postId, DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER, true),
            applicationDeadlineSummerSemester:
                (string)get_post_meta($postId, DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER, true),
            detailsAndNotes: $this->bilingualPostMeta($post, DegreeProgram::DETAILS_AND_NOTES),
            languageSkills: $this->bilingualPostMeta($post, DegreeProgram::LANGUAGE_SKILLS),
            languageSkillsHumanitiesFaculty:
                (string)get_post_meta($postId, DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY, true),
            germanLanguageSkillsForInternationalStudents:
                $this->bilingualLinkFromTerm(
                    $this->firstTerm(
                        $post,
                        GermanLanguageSkillsForInternationalStudentsTaxonomy::KEY,
                    )
                ),
            startOfSemester: $this->bilingualLinkFromOption(DegreeProgram::START_OF_SEMESTER),
            semesterDates: $this->bilingualLinkFromOption(DegreeProgram::SEMESTER_DATES),
            examinationsOffice: $this->bilingualLinkFromTerm(
                $this->firstTerm($post, ExaminationsOfficeTaxonomy::KEY)
            ),
            examinationRegulations: $this->bilingualLinkFromTerm(
                $this->firstTerm($post, ExaminationRegulationsTaxonomy::KEY)
            ),
            moduleHandbook: (string)get_post_meta($postId, DegreeProgram::MODULE_HANDBOOK, true),
            url: $this->bilingualPostMeta($post, DegreeProgram::URL),
            department: $this->bilingualLinkFromOption(DegreeProgram::DEPARTMENT),
            studentAdvice: $this->bilingualLinkFromOption(DegreeProgram::STUDENT_ADVICE),
            subjectSpecificAdvice: $this->bilingualLinkFromTerm(
                $this->firstTerm($post, SubjectSpecificAdviceTaxonomy::KEY)
            ),
            serviceCenters: $this->bilingualLinkFromOption(DegreeProgram::SERVICE_CENTERS),
            studentRepresentatives: (string)get_post_meta($postId, DegreeProgram::STUDENT_REPRESENTATIVES, true),
            semesterFee: $this->bilingualLinkFromOption(DegreeProgram::SEMESTER_FEE),
            degreeProgramFees: $this->bilingualPostMeta($post, DegreeProgram::DEGREE_PROGRAM_FEES),
            abroadOpportunities: $this->bilingualLinkFromOption(DegreeProgram::ABROAD_OPPORTUNITIES),
            keywords: $this->bilingualTermsList($post, KeywordTaxonomy::KEY),
            areaOfStudy: $this->bilingualTermLinks($post, AreaOfStudyTaxonomy::KEY),
            combinations: $this->idsFromPostMeta($postId, DegreeProgram::COMBINATIONS),
            limitedCombinations: $this->idsFromPostMeta($postId, DegreeProgram::LIMITED_COMBINATIONS),
        );
    }

    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw
    {
        try {
            $degreeProgram = $this->getById($degreeProgramId);
            return $this->transformEntityToView($degreeProgram);
        } catch (RuntimeException) {
            return null;
        }
    }

    private function transformEntityToView(DegreeProgram $degreeProgram): DegreeProgramViewRaw
    {
        $data = $degreeProgram->asArray();
        return new DegreeProgramViewRaw(
            $data[DegreeProgram::ID],
            $data[DegreeProgram::FEATURED_IMAGE],
            $data[DegreeProgram::TEASER_IMAGE],
            $data[DegreeProgram::TITLE],
            $data[DegreeProgram::SUBTITLE],
            $data[DegreeProgram::STANDARD_DURATION],
            $data[DegreeProgram::FEE_REQUIRED],
            $data[DegreeProgram::START],
            $data[DegreeProgram::NUMBER_OF_STUDENTS],
            $data[DegreeProgram::TEACHING_LANGUAGE],
            $data[DegreeProgram::ATTRIBUTES],
            $data[DegreeProgram::DEGREE],
            $data[DegreeProgram::FACULTY],
            $data[DegreeProgram::LOCATION],
            $data[DegreeProgram::SUBJECT_GROUPS],
            $data[DegreeProgram::VIDEOS],
            $data[DegreeProgram::META_DESCRIPTION],
            $data[DegreeProgram::CONTENT],
            $data[DegreeProgram::ADMISSION_REQUIREMENTS],
            $data[DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS],
            $data[DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER],
            $data[DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER],
            $data[DegreeProgram::DETAILS_AND_NOTES],
            $data[DegreeProgram::LANGUAGE_SKILLS],
            $data[DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY],
            $data[DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS],
            $data[DegreeProgram::START_OF_SEMESTER],
            $data[DegreeProgram::SEMESTER_DATES],
            $data[DegreeProgram::EXAMINATIONS_OFFICE],
            $data[DegreeProgram::EXAMINATION_REGULATIONS],
            $data[DegreeProgram::MODULE_HANDBOOK],
            $data[DegreeProgram::URL],
            $data[DegreeProgram::DEPARTMENT],
            $data[DegreeProgram::STUDENT_ADVICE],
            $data[DegreeProgram::SUBJECT_SPECIFIC_ADVICE],
            $data[DegreeProgram::SERVICE_CENTERS],
            $data[DegreeProgram::STUDENT_REPRESENTATIVES],
            $data[DegreeProgram::SEMESTER_FEE],
            $data[DegreeProgram::DEGREE_PROGRAM_FEES],
            $data[DegreeProgram::ABROAD_OPPORTUNITIES],
            $data[DegreeProgram::KEYWORDS],
            $data[DegreeProgram::AREA_OF_STUDY],
            $data[DegreeProgram::COMBINATIONS],
            $data[DegreeProgram::LIMITED_COMBINATIONS],
        );
    }

    public function findTranslated(
        DegreeProgramId $degreeProgramId,
        string $languageCode
    ): ?DegreeProgramViewTranslated {

        $raw = $this->findRaw($degreeProgramId);
        if (!$raw instanceof DegreeProgramViewRaw) {
            return null;
        }

        return new DegreeProgramViewTranslated(
            id: $degreeProgramId,
            featuredImage: $raw->featuredImage(),
            teaserImage: $raw->teaserImage(),
            title: $raw->title()->asString($languageCode),
            subtitle: $raw->subtitle()->asString($languageCode),
            standardDuration: $raw->standardDuration(),
            feeRequired: $raw->isFeeRequired(),
            start: $raw->start()->asArrayOfStrings($languageCode),
            numberOfStudents: $raw->numberOfStudents()->asString(),
            teachingLanguage: $raw->teachingLanguage()->asString($languageCode),
            attributes: $raw->attributes()->asArrayOfStrings($languageCode),
            degree: DegreeTranslated::fromDegree($raw->degree(), $languageCode),
            faculty: Link::fromMultilingualLink($raw->faculty(), $languageCode),
            location: $raw->location()->asString($languageCode),
            subjectGroups: $raw->subjectGroups()->asArrayOfStrings($languageCode),
            videos: $this->formattedVideos($raw->videos()),
            metaDescription: $raw->metaDescription()->asString($languageCode),
            content: ContentTranslated::fromContent($raw->content(), $languageCode),
            application: Link::fromMultilingualLink($raw->admissionRequirements()->requirementsForDegree($raw->degree()), $languageCode),
            contentRelatedMasterRequirements: $raw->contentRelatedMasterRequirements()->asString($languageCode),
            applicationDeadlineWinterSemester: $raw->applicationDeadlineWinterSemester(),
            applicationDeadlineSummerSemester: $raw->applicationDeadlineSummerSemester(),
            detailsAndNotes: $raw->detailsAndNotes()->asString($languageCode),
            languageSkills: $raw->languageSkills()->asString($languageCode),
            languageSkillsHumanitiesFaculty: $raw->languageSkillsHumanitiesFaculty(),
            germanLanguageSkillsForInternationalStudents: Link::fromMultilingualLink(
                $raw->germanLanguageSkillsForInternationalStudents(),
                $languageCode
            ),
            startOfSemester: Link::fromMultilingualLink($raw->startOfSemester(), $languageCode),
            semesterDates: Link::fromMultilingualLink($raw->semesterDates(), $languageCode),
            examinationsOffice: Link::fromMultilingualLink($raw->examinationsOffice(), $languageCode),
            examinationRegulations: Link::fromMultilingualLink($raw->examinationRegulations(), $languageCode),
            moduleHandbook: $raw->moduleHandbook(),
            url: $raw->url()->asString($languageCode),
            department: Link::fromMultilingualLink($raw->department(), $languageCode),
            studentAdvice: Link::fromMultilingualLink($raw->studentAdvice(), $languageCode),
            subjectSpecificAdvice: Link::fromMultilingualLink($raw->subjectSpecificAdvice(), $languageCode),
            serviceCenters: Link::fromMultilingualLink($raw->serviceCenters(), $languageCode),
            studentRepresentatives: $raw->studentRepresentatives(),
            semesterFee: Link::fromMultilingualLink($raw->semesterFee(), $languageCode),
            degreeProgramFees: $raw->degreeProgramFees()->asString($languageCode),
            abroadOpportunities: Link::fromMultilingualLink($raw->abroadOpportunities(), $languageCode),
            combinations: $this->relatedDegreePrograms($raw->combinations()->asArray(), $languageCode),
            limitedCombinations: $this->relatedDegreePrograms($raw->limitedCombinations()->asArray(), $languageCode),
        );
    }

    private function numberOfStudents(WP_Post $post): NumberOfStudents
    {
        $firstTerm = $this->firstTerm($post, NumberOfStudentsTaxonomy::KEY);
        if (!$firstTerm instanceof WP_Term) {
            return NumberOfStudents::empty();
        }

        return NumberOfStudents::new(
            'term:' . (string) $firstTerm->term_id,
            term_description($firstTerm->term_id)
        );
    }

    private function firstTerm(WP_Post $post, string $taxonomy): ?WP_Term
    {
        $terms = get_the_terms($post, $taxonomy);
        if (!is_array($terms)) {
            return null;
        }

        if (!isset($terms[0]) || !$terms[0] instanceof WP_Term) {
            return null;
        }

        return $terms[0];
    }

    private function degree(WP_Post $post): Degree
    {
        // TODO: should we verify if is this not a parent term?
        $term = $this->firstTerm($post, DegreeTaxonomy::KEY);

        if (!$term instanceof WP_Term) {
            return Degree::empty();
        }

        return Degree::new(
            'term:' . (string) $term->term_id,
            $this->bilingualTermName($term),
            $this->bilingualTermMeta($term, Degree::ABBREVIATION)
        );
    }

    private function formattedVideos(ArrayOfStrings $videos): ArrayOfStrings
    {
        $result = [];
        foreach ($videos as $video) {
            // $video could be shortcode or link
            $result[] = (string) apply_filters('the_content', $video);
        }

        return ArrayOfStrings::new(...$result);
    }

    private function contentItem(WP_Post $post, string $key): ContentItem
    {
        return ContentItem::new(
            $this->bilingualOption($key),
            $this->bilingualPostMeta($post, $key),
        );
    }

    private function idsFromPostMeta(int $postId, string $key): DegreeProgramIds
    {
        $metas = (array) get_post_meta($postId, $key);
        $result = [];
        foreach ($metas as $meta) {
            $castedMeta = (int) $meta;
            if ($castedMeta === 0) {
                continue;
            }

            $result[] = $castedMeta;
        }

        return DegreeProgramIds::fromArray($result);
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

    /**
     * While it violates the DDD consistency principle, we save the Degree Program entity
     * only partially to leverage WordPress functionality provided out-of-the-box.
     *
     * We only save post metas and assign terms to the WordPress post.
     * Native post properties like post title are saved by WordPress functionality.
     * Shared properties like term metas and options are saved separately.
     */
    public function save(DegreeProgram $degreeProgram): void
    {
        $degreeProgramViewRaw = $this->transformEntityToView($degreeProgram);
        $postId = $degreeProgramViewRaw->id()->asInt();

        set_post_thumbnail($postId, $degreeProgramViewRaw->featuredImage()->id());

        $metas = [
            DegreeProgram::TEASER_IMAGE =>
                $degreeProgramViewRaw->teaserImage()->id(),
            BilingualRepository::addEnglishSuffix('title') =>
                $degreeProgramViewRaw->title()->inEnglish(),
            DegreeProgram::STANDARD_DURATION =>
                $degreeProgramViewRaw->standardDuration(),
            DegreeProgram::FEE_REQUIRED =>
                $degreeProgramViewRaw->isFeeRequired(),
            DegreeProgram::VIDEOS =>
                $degreeProgramViewRaw->videos(),
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER =>
                $degreeProgramViewRaw->applicationDeadlineWinterSemester(),
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER =>
                $degreeProgramViewRaw->applicationDeadlineSummerSemester(),
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY =>
                $degreeProgramViewRaw->languageSkillsHumanitiesFaculty(),
            DegreeProgram::MODULE_HANDBOOK =>
                $degreeProgramViewRaw->moduleHandbook(),
            DegreeProgram::STUDENT_REPRESENTATIVES =>
                $degreeProgramViewRaw->studentRepresentatives(),
        ];

        foreach ($metas as $key => $value) {
            update_post_meta($postId, $key, $value);
        }

        $content = $degreeProgramViewRaw->content();
        $bilingualMetas = [
            $degreeProgramViewRaw->subtitle(),
            $degreeProgramViewRaw->metaDescription(),
            $content->about()->description(),
            $content->structure()->description(),
            $content->specializations()->description(),
            $content->qualitiesAndSkills()->description(),
            $content->whyShouldStudy()->description(),
            $content->careerProspects()->description(),
            $content->specialFeatures()->description(),
            $content->testimonials()->description(),
            $degreeProgramViewRaw->contentRelatedMasterRequirements(),
            $degreeProgramViewRaw->detailsAndNotes(),
            $degreeProgramViewRaw->languageSkills(),
            $degreeProgramViewRaw->url(),
            $degreeProgramViewRaw->degreeProgramFees(),
        ];

        foreach ($bilingualMetas as $bilingualMeta) {
            $this->saveBilingualPostMeta($postId, $bilingualMeta);
        }

        $admissionRequirements = $degreeProgramViewRaw->admissionRequirements();
        $terms = [
            SemesterTaxonomy::KEY =>
                $degreeProgramViewRaw->start(),
            NumberOfStudentsTaxonomy::KEY =>
                $degreeProgramViewRaw->numberOfStudents(),
            TeachingLanguageTaxonomy::KEY =>
                $degreeProgramViewRaw->teachingLanguage(),
            AttributeTaxonomy::KEY =>
                $degreeProgramViewRaw->attributes(),
            DegreeTaxonomy::KEY =>
                $degreeProgramViewRaw->degree(),
            FacultyTaxonomy::KEY =>
                $degreeProgramViewRaw->faculty(),
            StudyLocationTaxonomy::KEY =>
                $degreeProgramViewRaw->location(),
            SubjectGroupTaxonomy::KEY =>
                $degreeProgramViewRaw->subjectGroups(),
            BachelorOrTeachingDegreeAdmissionRequirementTaxonomy::KEY =>
                $admissionRequirements->bachelorOrTeachingDegree(),
            TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy::KEY =>
                $admissionRequirements->teachingDegreeHigherSemester(),
            MasterDegreeAdmissionRequirementTaxonomy::KEY =>
                $admissionRequirements->master(),
            GermanLanguageSkillsForInternationalStudentsTaxonomy::KEY =>
                $degreeProgramViewRaw->germanLanguageSkillsForInternationalStudents(),
            ExaminationsOfficeTaxonomy::KEY =>
                $degreeProgramViewRaw->examinationsOffice(),
            ExaminationRegulationsTaxonomy::KEY =>
                $degreeProgramViewRaw->examinationRegulations(),
            SubjectSpecificAdviceTaxonomy::KEY =>
                $degreeProgramViewRaw->subjectSpecificAdvice(),
            KeywordTaxonomy::KEY =>
                $degreeProgramViewRaw->keywords(),
            AreaOfStudyTaxonomy::KEY =>
                $degreeProgramViewRaw->areaOfStudy(),
        ];

        foreach ($terms as $taxonomy => $multilingualStructure) {
            wp_set_object_terms(
                $postId,
                $this->termIdsList($multilingualStructure),
                $taxonomy
            );
        }

        $data = $degreeProgram->asArray();
        $this->persistCombinations(
            $postId,
            DegreeProgram::COMBINATIONS,
            $data[DegreeProgram::COMBINATIONS_CHANGESET],
        );
        $this->persistCombinations(
            $postId,
            DegreeProgram::LIMITED_COMBINATIONS,
            $data[DegreeProgram::LIMITED_COMBINATIONS_CHANGESET],
        );
    }

    /**
     * Bidirectional many-to-many relationship implementation.
     */
    private function persistCombinations(
        int $postId,
        string $key,
        IntegersListChangeset $arrayChangeset
    ): void {

        foreach ($arrayChangeset->removed() as $item) {
            delete_post_meta($postId, $key, $item);
            delete_post_meta($item, $key, $postId);
        }

        foreach ($arrayChangeset->added() as $item) {
            add_post_meta($postId, $key, $item);
            add_post_meta($item, $key, $postId);
        }
    }
}
