<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use JsonSerializable;

/**
 * @psalm-import-type DegreeTranslatedType from DegreeTranslated
 * @psalm-import-type AdmissionRequirementsTranslatedType from AdmissionRequirementsTranslated
 * @psalm-import-type AdmissionRequirementTranslatedType from AdmissionRequirementTranslated
 * @psalm-import-type LinkType from Link
 * @psalm-import-type ContentTranslatedType from ContentTranslated
 * @psalm-import-type RelatedDegreeProgramType from RelatedDegreeProgram
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-import-type ImageViewType from ImageView
 * @psalm-import-type NumberOfStudentsType from NumberOfStudents
 * @psalm-type DegreeProgramTranslation = array{
 *     link: string,
 *     slug: string,
 *     lang: LanguageCodes,
 *     featured_image: ImageViewType,
 *     teaser_image: ImageViewType,
 *     title: string,
 *     subtitle: string,
 *     standard_duration: string,
 *     fee_required: bool,
 *     start: array<string>,
 *     number_of_students: NumberOfStudentsType,
 *     teaching_language: string,
 *     attributes: array<string>,
 *     degree: DegreeTranslatedType,
 *     faculty: array<LinkType>,
 *     location: array<string>,
 *     subject_groups: array<string>,
 *     videos: array<array-key, string>,
 *     meta_description: string,
 *     content: ContentTranslatedType,
 *     admission_requirements: AdmissionRequirementsTranslatedType,
 *     admission_requirement_link: AdmissionRequirementTranslatedType|null,
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
 *     department: string,
 *     student_advice: LinkType,
 *     subject_specific_advice: LinkType,
 *     service_centers: LinkType,
 *     info_brochure: string,
 *     semester_fee: LinkType,
 *     degree_program_fees: string,
 *     abroad_opportunities: LinkType,
 *     keywords: array<string>,
 *     area_of_study: array<LinkType>,
 *     combinations: array<RelatedDegreeProgramType>,
 *     limited_combinations: array<RelatedDegreeProgramType>,
 *     notes_for_international_applicants: LinkType,
 *     student_initiatives: LinkType,
 *     apply_now_link: LinkType,
 *     entry_text: string,
 * }
 * @psalm-type DegreeProgramViewTranslatedArrayType = DegreeProgramTranslation & array{
 *      id: int,
 *      translations: array<LanguageCodes, DegreeProgramTranslation>,
 * }
 */
final class DegreeProgramViewTranslated implements JsonSerializable
{
    public const LINK = 'link';
    public const LANG = 'lang';
    public const ADMISSION_REQUIREMENT_LINK = 'admission_requirement_link';
    public const TRANSLATIONS = 'translations';

    /** @var array<LanguageCodes, DegreeProgramViewTranslated> */
    private array $translations = [];

    public function __construct(
        private DegreeProgramId $id,
        private string $link,
        private string $slug,
        /**
         * @var LanguageCodes $lang
         */
        private string $lang,
        private ImageView $featuredImage,
        private ImageView $teaserImage,
        private string $title,
        private string $subtitle,
        private string $standardDuration,
        private bool $feeRequired,
        private ArrayOfStrings $start,
        private NumberOfStudents $numberOfStudents,
        private string $teachingLanguage,
        private ArrayOfStrings $attributes,
        private DegreeTranslated $degree,
        private Links $faculty,
        private ArrayOfStrings $location,
        private ArrayOfStrings $subjectGroups,
        private ArrayOfStrings $videos,
        private string $metaDescription,
        private ContentTranslated $content,
        private AdmissionRequirementsTranslated $admissionRequirements,
        private ?AdmissionRequirementTranslated $admissionRequirementLink,
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
        private string $department,
        private Link $studentAdvice,
        private Link $subjectSpecificAdvice,
        private Link $serviceCenters,
        private string $infoBrochure,
        private Link $semesterFee,
        private string $degreeProgramFees,
        private Link $abroadOpportunities,
        private ArrayOfStrings $keywords,
        private Links $areaOfStudy,
        private RelatedDegreePrograms $combinations,
        private RelatedDegreePrograms $limitedCombinations,
        private Link $notesForInternationalApplicants,
        private Link $studentInitiatives,
        private Link $applyNowLink,
        private string $entryText,
    ) {
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public static function empty(int $id, string $languageCode): self
    {
        return new self(
            DegreeProgramId::fromInt($id),
            link: '',
            slug: '',
            lang: $languageCode,
            featuredImage: ImageView::empty(),
            teaserImage: ImageView::empty(),
            title: '',
            subtitle: '',
            standardDuration: '',
            feeRequired: false,
            start: ArrayOfStrings::new(),
            numberOfStudents: NumberOfStudents::empty(),
            teachingLanguage: '',
            attributes: ArrayOfStrings::new(),
            degree: DegreeTranslated::new('', '', null),
            faculty: Links::new(),
            location: ArrayOfStrings::new(),
            subjectGroups: ArrayOfStrings::new(),
            videos: ArrayOfStrings::new(),
            metaDescription: '',
            content: ContentTranslated::new(
                ...array_fill(0, 8, ContentItemTranslated::new('', ''))
            ),
            admissionRequirements: AdmissionRequirementsTranslated::new([]),
            admissionRequirementLink: AdmissionRequirementTranslated::new(Link::empty(), null),
            contentRelatedMasterRequirements: '',
            applicationDeadlineWinterSemester: '',
            applicationDeadlineSummerSemester: '',
            detailsAndNotes: '',
            languageSkills: '',
            languageSkillsHumanitiesFaculty: '',
            germanLanguageSkillsForInternationalStudents: Link::empty(),
            startOfSemester: Link::empty(),
            semesterDates: Link::empty(),
            examinationsOffice: Link::empty(),
            examinationRegulations: '',
            moduleHandbook: '',
            url: '',
            department: '',
            studentAdvice: Link::empty(),
            subjectSpecificAdvice: Link::empty(),
            serviceCenters: Link::empty(),
            infoBrochure: '',
            semesterFee: Link::empty(),
            degreeProgramFees: '',
            abroadOpportunities: Link::empty(),
            keywords: ArrayOfStrings::new(),
            areaOfStudy: Links::new(),
            combinations:  RelatedDegreePrograms::new(),
            limitedCombinations: RelatedDegreePrograms::new(),
            notesForInternationalApplicants: Link::empty(),
            studentInitiatives: Link::empty(),
            applyNowLink: Link::empty(),
            entryText: '',
        );
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
            link: $data[self::LINK],
            slug: $data[DegreeProgram::SLUG],
            lang: $data[self::LANG],
            featuredImage: ImageView::fromArray($data[DegreeProgram::FEATURED_IMAGE]),
            teaserImage: ImageView::fromArray($data[DegreeProgram::TEASER_IMAGE]),
            title: $data[DegreeProgram::TITLE],
            subtitle: $data[DegreeProgram::SUBTITLE],
            standardDuration: $data[DegreeProgram::STANDARD_DURATION],
            feeRequired: $data[DegreeProgram::FEE_REQUIRED],
            start: ArrayOfStrings::new(...$data[DegreeProgram::START]),
            numberOfStudents: NumberOfStudents::fromArray($data[DegreeProgram::NUMBER_OF_STUDENTS]),
            teachingLanguage: $data[DegreeProgram::TEACHING_LANGUAGE],
            attributes: ArrayOfStrings::new(...$data[DegreeProgram::ATTRIBUTES]),
            degree: DegreeTranslated::fromArray($data[DegreeProgram::DEGREE]),
            faculty: Links::fromArray($data[DegreeProgram::FACULTY]),
            location: ArrayOfStrings::new(...$data[DegreeProgram::LOCATION]),
            subjectGroups: ArrayOfStrings::new(...$data[DegreeProgram::SUBJECT_GROUPS]),
            videos: ArrayOfStrings::new(...$data[DegreeProgram::VIDEOS]),
            metaDescription: $data[DegreeProgram::META_DESCRIPTION],
            content: ContentTranslated::fromArray($data[DegreeProgram::CONTENT]),
            admissionRequirements: AdmissionRequirementsTranslated::fromArray($data[DegreeProgram::ADMISSION_REQUIREMENTS]),
            admissionRequirementLink: !empty($data[self::ADMISSION_REQUIREMENT_LINK])
                ? AdmissionRequirementTranslated::fromArray($data[self::ADMISSION_REQUIREMENT_LINK])
                : null,
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
            department: $data[DegreeProgram::DEPARTMENT],
            studentAdvice: Link::fromArray($data[DegreeProgram::STUDENT_ADVICE]),
            subjectSpecificAdvice: Link::fromArray($data[DegreeProgram::SUBJECT_SPECIFIC_ADVICE]),
            serviceCenters: Link::fromArray($data[DegreeProgram::SERVICE_CENTERS]),
            infoBrochure: $data[DegreeProgram::INFO_BROCHURE],
            semesterFee: Link::fromArray($data[DegreeProgram::SEMESTER_FEE]),
            degreeProgramFees: $data[DegreeProgram::DEGREE_PROGRAM_FEES],
            abroadOpportunities: Link::fromArray($data[DegreeProgram::ABROAD_OPPORTUNITIES]),
            keywords: ArrayOfStrings::new(...$data[DegreeProgram::KEYWORDS]),
            areaOfStudy: Links::fromArray($data[DegreeProgram::AREA_OF_STUDY]),
            combinations:  RelatedDegreePrograms::fromArray($data[DegreeProgram::COMBINATIONS]),
            limitedCombinations: RelatedDegreePrograms::fromArray($data[DegreeProgram::LIMITED_COMBINATIONS]),
            notesForInternationalApplicants: Link::fromArray($data[DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS]),
            studentInitiatives: Link::fromArray($data[DegreeProgram::STUDENT_INITIATIVES]),
            applyNowLink: Link::fromArray($data[DegreeProgram::APPLY_NOW_LINK]),
            entryText: $data[DegreeProgram::ENTRY_TEXT],
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
            self::LINK => $this->link,
            DegreeProgram::SLUG => $this->slug,
            self::LANG => $this->lang,
            DegreeProgram::FEATURED_IMAGE => $this->featuredImage->asArray(),
            DegreeProgram::TEASER_IMAGE => $this->teaserImage->asArray(),
            DegreeProgram::TITLE => $this->title,
            DegreeProgram::SUBTITLE => $this->subtitle,
            DegreeProgram::STANDARD_DURATION => $this->standardDuration,
            DegreeProgram::FEE_REQUIRED => $this->feeRequired,
            DegreeProgram::START => $this->start->getArrayCopy(),
            DegreeProgram::NUMBER_OF_STUDENTS => $this->numberOfStudents->asArray(),
            DegreeProgram::TEACHING_LANGUAGE => $this->teachingLanguage,
            DegreeProgram::ATTRIBUTES => $this->attributes->getArrayCopy(),
            DegreeProgram::DEGREE => $this->degree->asArray(),
            DegreeProgram::FACULTY => $this->faculty->asArray(),
            DegreeProgram::LOCATION => $this->location->getArrayCopy(),
            DegreeProgram::SUBJECT_GROUPS => $this->subjectGroups->getArrayCopy(),
            DegreeProgram::VIDEOS => $this->videos->getArrayCopy(),
            DegreeProgram::META_DESCRIPTION => $this->metaDescription,
            DegreeProgram::CONTENT => $this->content->asArray(),
            DegreeProgram::ADMISSION_REQUIREMENTS => $this->admissionRequirements->asArray(),
            self::ADMISSION_REQUIREMENT_LINK => $this->admissionRequirementLink()?->asArray(),
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
            DegreeProgram::DEPARTMENT => $this->department,
            DegreeProgram::STUDENT_ADVICE => $this->studentAdvice->asArray(),
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => $this->subjectSpecificAdvice->asArray(),
            DegreeProgram::SERVICE_CENTERS => $this->serviceCenters->asArray(),
            DegreeProgram::INFO_BROCHURE => $this->infoBrochure,
            DegreeProgram::SEMESTER_FEE => $this->semesterFee->asArray(),
            DegreeProgram::DEGREE_PROGRAM_FEES => $this->degreeProgramFees,
            DegreeProgram::ABROAD_OPPORTUNITIES => $this->abroadOpportunities->asArray(),
            DegreeProgram::KEYWORDS => $this->keywords->getArrayCopy(),
            DegreeProgram::AREA_OF_STUDY => $this->areaOfStudy->asArray(),
            DegreeProgram::COMBINATIONS => $this->combinations->asArray(),
            DegreeProgram::LIMITED_COMBINATIONS => $this->limitedCombinations->asArray(),
            DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS => $this->notesForInternationalApplicants->asArray(),
            DegreeProgram::STUDENT_INITIATIVES => $this->studentInitiatives->asArray(),
            DegreeProgram::APPLY_NOW_LINK => $this->applyNowLink->asArray(),
            DegreeProgram::ENTRY_TEXT => $this->entryText,
            self::TRANSLATIONS => $this->translationsAsArray(),
        ];
    }

    public function jsonSerialize(): array
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

    public function link(): string
    {
        return $this->link;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * @return LanguageCodes
     */
    public function lang(): string
    {
        return $this->lang;
    }

    public function featuredImage(): ImageView
    {
        return $this->featuredImage;
    }

    public function teaserImage(): ImageView
    {
        return $this->teaserImage;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function subtitle(): string
    {
        return $this->subtitle;
    }

    public function standardDuration(): string
    {
        return $this->standardDuration;
    }

    public function isFeeRequired(): bool
    {
        return $this->feeRequired;
    }

    public function start(): ArrayOfStrings
    {
        return $this->start;
    }

    public function numberOfStudents(): NumberOfStudents
    {
        return $this->numberOfStudents;
    }

    public function teachingLanguage(): string
    {
        return $this->teachingLanguage;
    }

    public function attributes(): ArrayOfStrings
    {
        return $this->attributes;
    }

    public function degree(): DegreeTranslated
    {
        return $this->degree;
    }

    public function faculty(): Links
    {
        return $this->faculty;
    }

    public function location(): ArrayOfStrings
    {
        return $this->location;
    }

    public function subjectGroups(): ArrayOfStrings
    {
        return $this->subjectGroups;
    }

    public function videos(): ArrayOfStrings
    {
        return $this->videos;
    }

    public function metaDescription(): string
    {
        return $this->metaDescription;
    }

    public function content(): ContentTranslated
    {
        return $this->content;
    }

    public function admissionRequirements(): AdmissionRequirementsTranslated
    {
        return $this->admissionRequirements;
    }

    public function admissionRequirementLink(): ?AdmissionRequirementTranslated
    {
        return $this->admissionRequirementLink;
    }

    public function contentRelatedMasterRequirements(): string
    {
        return $this->contentRelatedMasterRequirements;
    }

    public function applicationDeadlineWinterSemester(): string
    {
        return $this->applicationDeadlineWinterSemester;
    }

    public function applicationDeadlineSummerSemester(): string
    {
        return $this->applicationDeadlineSummerSemester;
    }

    public function detailsAndNotes(): string
    {
        return $this->detailsAndNotes;
    }

    public function languageSkills(): string
    {
        return $this->languageSkills;
    }

    public function languageSkillsHumanitiesFaculty(): string
    {
        return $this->languageSkillsHumanitiesFaculty;
    }

    public function germanLanguageSkillsForInternationalStudents(): Link
    {
        return $this->germanLanguageSkillsForInternationalStudents;
    }

    public function startOfSemester(): Link
    {
        return $this->startOfSemester;
    }

    public function semesterDates(): Link
    {
        return $this->semesterDates;
    }

    public function examinationsOffice(): Link
    {
        return $this->examinationsOffice;
    }

    public function examinationRegulations(): string
    {
        return $this->examinationRegulations;
    }

    public function moduleHandbook(): string
    {
        return $this->moduleHandbook;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function department(): string
    {
        return $this->department;
    }

    public function studentAdvice(): Link
    {
        return $this->studentAdvice;
    }

    public function subjectSpecificAdvice(): Link
    {
        return $this->subjectSpecificAdvice;
    }

    public function serviceCenters(): Link
    {
        return $this->serviceCenters;
    }

    public function infoBrochure(): string
    {
        return $this->infoBrochure;
    }

    public function semesterFee(): Link
    {
        return $this->semesterFee;
    }

    public function degreeProgramFees(): string
    {
        return $this->degreeProgramFees;
    }

    public function abroadOpportunities(): Link
    {
        return $this->abroadOpportunities;
    }

    public function keywords(): ArrayOfStrings
    {
        return $this->keywords;
    }

    public function areaOfStudy(): Links
    {
        return $this->areaOfStudy;
    }

    public function combinations(): RelatedDegreePrograms
    {
        return $this->combinations;
    }

    public function limitedCombinations(): RelatedDegreePrograms
    {
        return $this->limitedCombinations;
    }

    public function notesForInternationalApplicants(): Link
    {
        return $this->notesForInternationalApplicants;
    }

    public function studentInitiatives(): Link
    {
        return $this->studentInitiatives;
    }

    public function applyNowLink(): Link
    {
        return $this->applyNowLink;
    }

    public function entryText(): string
    {
        return $this->entryText;
    }
}
