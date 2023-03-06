<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use JsonSerializable;

/**
 * @psalm-import-type DegreeTranslatedType from DegreeTranslated
 * @psalm-import-type LinkType from Link
 * @psalm-import-type ContentTranslatedType from ContentTranslated
 * @psalm-import-type RelatedDegreeProgramType from RelatedDegreeProgram
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type DegreeProgramTranslation = array{
 *     slug: string,
 *     lang: LanguageCodes,
 *     featured_image: array{id: int, url: string},
 *     teaser_image: array{id: int, url: string},
 *     title: string,
 *     subtitle: string,
 *     standard_duration: int,
 *     fee_required: bool,
 *     start: array<string>,
 *     number_of_students: string,
 *     teaching_language: string,
 *     attributes: array<string>,
 *     degree: DegreeTranslatedType,
 *     faculty: array<LinkType>,
 *     location: array<string>,
 *     subject_groups: array<string>,
 *     videos: array<array-key, string>,
 *     meta_description: string,
 *     content: ContentTranslatedType,
 *     application: LinkType,
 *     content_related_master_requirements: string,
 *     application_deadline_winter_semester: string,
 *     application_deadline_summer_semester: string,
 *     details_and_notes: string,
 *     language_skills: string,
 *     language_skills_humanities_faculty: string,
 *     german_language_skills_for_international_students: LinkType,
 *     start_of_semester: LinkType,
 *     semester_dates: LinkType,
 *     examinations_office: LinkType,
 *     examination_regulations: string,
 *     module_handbook: string,
 *     url: string,
 *     department: LinkType,
 *     student_advice: LinkType,
 *     subject_specific_advice: LinkType,
 *     service_centers: LinkType,
 *     student_representatives: string,
 *     semester_fee: LinkType,
 *     degree_program_fees: string,
 *     abroad_opportunities: LinkType,
 *     keywords: array<string>,
 *     area_of_study: array<LinkType>,
 *     combinations: array<RelatedDegreeProgramType>,
 *     limited_combinations: array<RelatedDegreeProgramType>,
 * }
 * @psalm-type DegreeProgramViewTranslatedArrayType = DegreeProgramTranslation & array{
 *      id: int,
 *      translations: array<LanguageCodes, DegreeProgramTranslation>,
 * }
 */
final class DegreeProgramViewTranslated implements JsonSerializable
{
    public const LANG = 'lang';
    public const APPLICATION = 'application';
    public const TRANSLATIONS = 'translations';

    /** @var array<LanguageCodes, DegreeProgramViewTranslated> */
    private array $translations = [];

    public function __construct(
        private DegreeProgramId $id,
        private string $slug,
        /**
         * @var LanguageCodes $lang
         */
        private string $lang,
        private Image $featuredImage,
        private Image $teaserImage,
        private string $title,
        private string $subtitle,
        private int $standardDuration,
        private bool $feeRequired,
        private ArrayOfStrings $start,
        private string $numberOfStudents,
        private string $teachingLanguage,
        private ArrayOfStrings $attributes,
        private DegreeTranslated $degree,
        private Links $faculty,
        private ArrayOfStrings $location,
        private ArrayOfStrings $subjectGroups,
        private ArrayOfStrings $videos,
        private string $metaDescription,
        private ContentTranslated $content,
        /**
         * Bewerbung
         */
        private Link $application,
        private string $contentRelatedMasterRequirements,
        private string $applicationDeadlineWinterSemester,
        private string $applicationDeadlineSummerSemester,
        private string $detailsAndNotes,
        private string $languageSkills,
        private string $languageSkillsHumanitiesFaculty,
        private Link $germanLanguageSkillsForInternationalStudents,
        private Link $startOfSemester,
        private Link $semesterDates,
        private Link $examinationsOffice,
        private string $examinationRegulations,
        private string $moduleHandbook,
        private string $url,
        private Link $department,
        private Link $studentAdvice,
        private Link $subjectSpecificAdvice,
        private Link $serviceCenters,
        private string $studentRepresentatives,
        private Link $semesterFee,
        private string $degreeProgramFees,
        private Link $abroadOpportunities,
        private ArrayOfStrings $keywords,
        private Links $areaOfStudy,
        private RelatedDegreePrograms $combinations,
        private RelatedDegreePrograms $limitedCombinations,
    ) {
    }

    /**
     * @psalm-param DegreeProgramTranslation & array{
     *      id: int | numeric-string,
     *      translations?: array<LanguageCodes, DegreeProgramTranslation>,
     * } $data
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public static function fromArray(array $data): self
    {
        $main = new self(
            id: DegreeProgramId::fromInt((int) $data[DegreeProgram::ID]),
            slug: $data[DegreeProgram::SLUG],
            lang: $data[self::LANG],
            featuredImage: Image::fromArray($data[DegreeProgram::FEATURED_IMAGE]),
            teaserImage: Image::fromArray($data[DegreeProgram::TEASER_IMAGE]),
            title: $data[DegreeProgram::TITLE],
            subtitle: $data[DegreeProgram::SUBTITLE],
            standardDuration: $data[DegreeProgram::STANDARD_DURATION],
            feeRequired: $data[DegreeProgram::FEE_REQUIRED],
            start: ArrayOfStrings::new(...$data[DegreeProgram::START]),
            numberOfStudents: $data[DegreeProgram::NUMBER_OF_STUDENTS],
            teachingLanguage: $data[DegreeProgram::TEACHING_LANGUAGE],
            attributes: ArrayOfStrings::new(...$data[DegreeProgram::ATTRIBUTES]),
            degree: DegreeTranslated::fromArray($data[DegreeProgram::DEGREE]),
            faculty: Links::fromArray($data[DegreeProgram::FACULTY]),
            location: ArrayOfStrings::new(...$data[DegreeProgram::LOCATION]),
            subjectGroups: ArrayOfStrings::new(...$data[DegreeProgram::SUBJECT_GROUPS]),
            videos: ArrayOfStrings::new(...$data[DegreeProgram::VIDEOS]),
            metaDescription: $data[DegreeProgram::META_DESCRIPTION],
            content: ContentTranslated::fromArray($data[DegreeProgram::CONTENT]),
            application: Link::fromArray($data[self::APPLICATION]),
            contentRelatedMasterRequirements: $data[DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS],
            applicationDeadlineWinterSemester: $data[DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER],
            applicationDeadlineSummerSemester: $data[DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER],
            detailsAndNotes: $data[DegreeProgram::DETAILS_AND_NOTES],
            languageSkills: $data[DegreeProgram::LANGUAGE_SKILLS],
            languageSkillsHumanitiesFaculty: $data[DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY],
            germanLanguageSkillsForInternationalStudents: Link::fromArray($data[DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS]),
            startOfSemester: Link::fromArray($data[DegreeProgram::START_OF_SEMESTER]),
            semesterDates: Link::fromArray($data[DegreeProgram::SEMESTER_DATES]),
            examinationsOffice: Link::fromArray($data[DegreeProgram::EXAMINATIONS_OFFICE]),
            examinationRegulations: $data[DegreeProgram::EXAMINATION_REGULATIONS],
            moduleHandbook: $data[DegreeProgram::MODULE_HANDBOOK],
            url: $data[DegreeProgram::URL],
            department: Link::fromArray($data[DegreeProgram::DEPARTMENT]),
            studentAdvice: Link::fromArray($data[DegreeProgram::STUDENT_ADVICE]),
            subjectSpecificAdvice: Link::fromArray($data[DegreeProgram::SUBJECT_SPECIFIC_ADVICE]),
            serviceCenters: Link::fromArray($data[DegreeProgram::SERVICE_CENTERS]),
            studentRepresentatives: $data[DegreeProgram::STUDENT_REPRESENTATIVES],
            semesterFee: Link::fromArray($data[DegreeProgram::SEMESTER_FEE]),
            degreeProgramFees: $data[DegreeProgram::DEGREE_PROGRAM_FEES],
            abroadOpportunities: Link::fromArray($data[DegreeProgram::ABROAD_OPPORTUNITIES]),
            keywords: ArrayOfStrings::new(...$data[DegreeProgram::KEYWORDS]),
            areaOfStudy: Links::fromArray($data[DegreeProgram::AREA_OF_STUDY]),
            combinations:  RelatedDegreePrograms::fromArray($data[DegreeProgram::COMBINATIONS]),
            limitedCombinations: RelatedDegreePrograms::fromArray($data[DegreeProgram::LIMITED_COMBINATIONS]),
        );

        if (empty($data[self::TRANSLATIONS])) {
            return $main;
        }

        foreach ($data[self::TRANSLATIONS] as $translationData) {
            $translationData[DegreeProgram::ID] = $data[DegreeProgram::ID];
            $main = $main->withTranslation(self::fromArray($translationData), $translationData[self::LANG]);
        }

        return $main;
    }

    /**
     * @return DegreeProgramViewTranslatedArrayType
     */
    public function asArray(): array
    {
        return [
            DegreeProgram::ID => $this->id->asInt(),
            DegreeProgram::SLUG => $this->slug,
            self::LANG => $this->lang,
            DegreeProgram::FEATURED_IMAGE => $this->featuredImage->asArray(),
            DegreeProgram::TEASER_IMAGE => $this->teaserImage->asArray(),
            DegreeProgram::TITLE => $this->title,
            DegreeProgram::SUBTITLE => $this->subtitle,
            DegreeProgram::STANDARD_DURATION => $this->standardDuration,
            DegreeProgram::FEE_REQUIRED => $this->feeRequired,
            DegreeProgram::START => $this->start->getArrayCopy(),
            DegreeProgram::NUMBER_OF_STUDENTS => $this->numberOfStudents,
            DegreeProgram::TEACHING_LANGUAGE => $this->teachingLanguage,
            DegreeProgram::ATTRIBUTES => $this->attributes->getArrayCopy(),
            DegreeProgram::DEGREE => $this->degree->asArray(),
            DegreeProgram::FACULTY => $this->faculty->asArray(),
            DegreeProgram::LOCATION => $this->location->getArrayCopy(),
            DegreeProgram::SUBJECT_GROUPS => $this->subjectGroups->getArrayCopy(),
            DegreeProgram::VIDEOS => $this->videos->getArrayCopy(),
            DegreeProgram::META_DESCRIPTION => $this->metaDescription,
            DegreeProgram::CONTENT => $this->content->asArray(),
            self::APPLICATION => $this->application->asArray(),
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS => $this->contentRelatedMasterRequirements,
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER => $this->applicationDeadlineWinterSemester,
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => $this->applicationDeadlineSummerSemester,
            DegreeProgram::DETAILS_AND_NOTES => $this->detailsAndNotes,
            DegreeProgram::LANGUAGE_SKILLS => $this->languageSkills,
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY => $this->languageSkillsHumanitiesFaculty,
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS =>
                $this->germanLanguageSkillsForInternationalStudents->asArray(),
            DegreeProgram::START_OF_SEMESTER => $this->startOfSemester->asArray(),
            DegreeProgram::SEMESTER_DATES => $this->semesterDates->asArray(),
            DegreeProgram::EXAMINATIONS_OFFICE => $this->examinationsOffice->asArray(),
            DegreeProgram::EXAMINATION_REGULATIONS => $this->examinationRegulations,
            DegreeProgram::MODULE_HANDBOOK => $this->moduleHandbook,
            DegreeProgram::URL => $this->url,
            DegreeProgram::DEPARTMENT => $this->department->asArray(),
            DegreeProgram::STUDENT_ADVICE => $this->studentAdvice->asArray(),
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => $this->subjectSpecificAdvice->asArray(),
            DegreeProgram::SERVICE_CENTERS => $this->serviceCenters->asArray(),
            DegreeProgram::STUDENT_REPRESENTATIVES => $this->studentRepresentatives,
            DegreeProgram::SEMESTER_FEE => $this->semesterFee->asArray(),
            DegreeProgram::DEGREE_PROGRAM_FEES => $this->degreeProgramFees,
            DegreeProgram::ABROAD_OPPORTUNITIES => $this->abroadOpportunities->asArray(),
            DegreeProgram::KEYWORDS => $this->keywords->getArrayCopy(),
            DegreeProgram::AREA_OF_STUDY => $this->areaOfStudy->asArray(),
            DegreeProgram::COMBINATIONS => $this->combinations->asArray(),
            DegreeProgram::LIMITED_COMBINATIONS => $this->limitedCombinations->asArray(),
            self::TRANSLATIONS => $this->translationsAsArray(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    public function withTranslation(
        DegreeProgramViewTranslated $degreeProgramViewTranslated,
        string $languageCode,
    ): self {

        $instance = clone $this;
        $instance->translations[$languageCode] = $degreeProgramViewTranslated;

        return $instance;
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    public function withBaseLang(string $languageCode): ?self
    {
        if ($languageCode === $this->lang) {
            return $this;
        }

        if (!isset($this->translations[$languageCode])) {
            return null;
        }

        $main = $this->translations[$languageCode];
        $translation = clone $this;
        $translation->translations = [];
        $main->withTranslation($translation, $languageCode);

        return $main;
    }

    /**
     * @return array<LanguageCodes, DegreeProgramTranslation>
     */
    private function translationsAsArray(): array
    {
        return array_map(static function (DegreeProgramViewTranslated $view): array {
            $result = $view->asArray();
            unset($result[DegreeProgram::ID], $result[self::TRANSLATIONS]);

            return $result;
        }, $this->translations);
    }

    public function id(): int
    {
        return $this->id->asInt();
    }
}
