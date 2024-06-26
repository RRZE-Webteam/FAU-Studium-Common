<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use Fau\DegreeProgram\Common\Domain\Event\DegreeProgramUpdated;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use Fau\DegreeProgram\Common\LanguageExtension\IntegersListChangeset;
use InvalidArgumentException;
use RuntimeException;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 * @psalm-import-type MultilingualLinkType from MultilingualLink
 * @psalm-import-type ContentType from Content
 * @psalm-import-type AdmissionRequirementsType from AdmissionRequirements
 * @psalm-import-type DegreeType from Degree
 * @psalm-import-type NumberOfStudentsType from NumberOfStudents
 * @psalm-import-type CampoKeysMap from CampoKeys
 * @psalm-type DegreeProgramArrayType = array{
 *     id: int,
 *     slug: MultilingualStringType,
 *     featured_image: array{id: int, url: string},
 *     teaser_image: array{id: int, url: string},
 *     title: MultilingualStringType,
 *     subtitle: MultilingualStringType,
 *     standard_duration: string,
 *     fee_required: bool,
 *     start: array<MultilingualStringType>,
 *     number_of_students: NumberOfStudentsType,
 *     teaching_language: MultilingualStringType,
 *     attributes: array<MultilingualStringType>,
 *     degree: DegreeType,
 *     faculty: array<MultilingualLinkType>,
 *     location: array<MultilingualStringType>,
 *     subject_groups: array<MultilingualStringType>,
 *     videos: array<array-key, string>,
 *     meta_description: MultilingualStringType,
 *     content: ContentType,
 *     admission_requirements: AdmissionRequirementsType,
 *     content_related_master_requirements: MultilingualStringType,
 *     application_deadline_winter_semester: string,
 *     application_deadline_summer_semester: string,
 *     details_and_notes: MultilingualStringType,
 *     language_skills: MultilingualStringType,
 *     language_skills_humanities_faculty: string,
 *     german_language_skills_for_international_students: MultilingualLinkType,
 *     start_of_semester: MultilingualLinkType,
 *     semester_dates: MultilingualLinkType,
 *     examinations_office: MultilingualLinkType,
 *     examination_regulations: string,
 *     module_handbook: string,
 *     url: MultilingualStringType,
 *     department: MultilingualStringType,
 *     student_advice: MultilingualLinkType,
 *     subject_specific_advice: MultilingualLinkType,
 *     service_centers: MultilingualLinkType,
 *     info_brochure: string,
 *     semester_fee: MultilingualLinkType,
 *     degree_program_fees: MultilingualStringType,
 *     abroad_opportunities: MultilingualLinkType,
 *     keywords: array<MultilingualStringType>,
 *     area_of_study: array<MultilingualLinkType>,
 *     combinations: array<int>,
 *     limited_combinations: array<int>,
 *     notes_for_international_applicants: MultilingualLinkType,
 *     student_initiatives: MultilingualLinkType,
 *     apply_now_link: MultilingualLinkType,
 *     entry_text: MultilingualStringType,
 *     campo_keys: CampoKeysMap,
 * }
 */
final class DegreeProgram
{
    public const ID = 'id';
    public const SLUG = 'slug';
    public const FEATURED_IMAGE = 'featured_image';
    public const TEASER_IMAGE = 'teaser_image';
    public const TITLE = 'title';
    public const SUBTITLE = 'subtitle';
    public const STANDARD_DURATION = 'standard_duration';
    public const FEE_REQUIRED = 'fee_required';
    public const START = 'start';
    public const NUMBER_OF_STUDENTS = 'number_of_students';
    public const TEACHING_LANGUAGE = 'teaching_language';
    public const ATTRIBUTES = 'attributes';
    public const DEGREE = 'degree';
    public const FACULTY = 'faculty';
    public const LOCATION = 'location';
    public const SUBJECT_GROUPS = 'subject_groups';
    public const VIDEOS = 'videos';
    public const META_DESCRIPTION = 'meta_description';
    public const CONTENT = 'content';
    public const ADMISSION_REQUIREMENTS = 'admission_requirements';
    public const CONTENT_RELATED_MASTER_REQUIREMENTS = 'content_related_master_requirements';
    public const APPLICATION_DEADLINE_WINTER_SEMESTER = 'application_deadline_winter_semester';
    public const APPLICATION_DEADLINE_SUMMER_SEMESTER = 'application_deadline_summer_semester';
    public const DETAILS_AND_NOTES = 'details_and_notes';
    public const LANGUAGE_SKILLS = 'language_skills';
    public const LANGUAGE_SKILLS_HUMANITIES_FACULTY = 'language_skills_humanities_faculty';
    public const GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS = 'german_language_skills_for_international_students';
    public const START_OF_SEMESTER = 'start_of_semester';
    public const SEMESTER_DATES = 'semester_dates';
    public const EXAMINATIONS_OFFICE = 'examinations_office';
    public const EXAMINATION_REGULATIONS = 'examination_regulations';
    public const MODULE_HANDBOOK = 'module_handbook';
    public const URL = 'url';
    public const DEPARTMENT = 'department';
    public const STUDENT_ADVICE = 'student_advice';
    public const SUBJECT_SPECIFIC_ADVICE = 'subject_specific_advice';
    public const SERVICE_CENTERS = 'service_centers';
    public const INFO_BROCHURE = 'info_brochure';
    public const SEMESTER_FEE = 'semester_fee';
    public const DEGREE_PROGRAM_FEES = 'degree_program_fees';
    public const ABROAD_OPPORTUNITIES = 'abroad_opportunities';
    public const KEYWORDS = 'keywords';
    public const AREA_OF_STUDY = 'area_of_study';
    public const ENTRY_TEXT = 'entry_text';
    public const COMBINATIONS = 'combinations';
    public const LIMITED_COMBINATIONS = 'limited_combinations';
    public const COMBINATIONS_CHANGESET = 'combinations_changeset';
    public const LIMITED_COMBINATIONS_CHANGESET = 'limited_combinations_changeset';
    public const NOTES_FOR_INTERNATIONAL_APPLICANTS = 'notes_for_international_applicants';
    public const STUDENT_INITIATIVES = 'student_initiatives';
    public const APPLY_NOW_LINK = 'apply_now_link';
    public const CAMPO_KEYS = 'campo_keys';

    private IntegersListChangeset $combinationsChangeset;
    private IntegersListChangeset $limitedCombinationsChangeset;
    /** @var array<object> */
    private array $events = [];

    public function __construct(
        private DegreeProgramId $id,
        private MultilingualString $slug,
        //--- At a glance (“Auf einen Blick”) ---//
        private Image $featuredImage,
        private Image $teaserImage,
        private MultilingualString $title,
        private MultilingualString $subtitle,
        /**
         * Duration of studies in semester
         * Regelstudienzeit
         */
        private string $standardDuration,
        /**
         * @var MultilingualList $start One or several semesters
         * Example: Summer Term, Winter Term
         * Studienbeginn
         */
        private MultilingualList $start,
        /**
         * Example: <50, 50 - 150
         * Studierendenzahl
         */
        private NumberOfStudents $numberOfStudents,
        /**
         * Unterrichtssprache
         */
        private MultilingualString $teachingLanguage,
        /**
         * Attribute
         */
        private MultilingualList $attributes,
        /**
         * Abschlüsse
         */
        private Degree $degree,
        /**
         * Fakultät
         */
        private MultilingualLinks $faculty,
        /**
         * Studienort
         */
        private MultilingualList $location,
        /**
         * Fächergruppen
         */
        private MultilingualList $subjectGroups,
        private ArrayOfStrings $videos,
        private MultilingualString $metaDescription,
        /**
         * Schlagworte
         */
        private MultilingualList $keywords,
        /**
         * Studienbereich
         */
        private MultilingualLinks $areaOfStudy,
        /**
         * Einstiegtext (werbend)
         */
        private MultilingualString $entryText,
        //--- Content (“Inhalte”) ---//
        private Content $content,
        //--- Admission requirements, application and enrollment (“Zugangsvoraussetzungen, Bewerbung und Einschreibung”) ---//
        /**
         * Bachelor's/teaching degrees, teaching degree at a higher semester, Master’s degree
         */
        private AdmissionRequirements $admissionRequirements,
        /**
         * Inhaltliche Zugangsvoraussetzungen Master
         */
        private MultilingualString $contentRelatedMasterRequirements,
        /**
         * Bewerbungsfrist Wintersemester
         */
        private string $applicationDeadlineWinterSemester,
        /**
         * Bewerbungsfrist Sommersemester
         */
        private string $applicationDeadlineSummerSemester,
        /**
         * Details und Anmerkungen
         */
        private MultilingualString $detailsAndNotes,
        /**
         * Sprachkenntnisse
         */
        private MultilingualString $languageSkills,
        /**
         * “Sprachkenntnisse nur für die Philosophische Fakultät und Fachbereich Theologie
         */
        private string $languageSkillsHumanitiesFaculty,
        /**
         * Sprachnachweise/Deutschkenntnisse für internationale Bewerberinnen und Bewerber
         */
        private MultilingualLink $germanLanguageSkillsForInternationalStudents,
        //--- Organization (organizational notes/links) (“Organisation (Organisatorische Hinweise/Links)”) --- //
        /**
         * Semesterstart
         * Shared property
         */
        private MultilingualLink $startOfSemester,
        /**
         * Semestertermine
         * Shared property
         */
        private MultilingualLink $semesterDates,
        /**
         * Prüfungsamt
         */
        private MultilingualLink $examinationsOffice,
        /**
         * Studien- und Prüfungsordnung
         */
        private string $examinationRegulations,
        /**
         * Modulhandbuch
         */
        private string $moduleHandbook,
        /**
         * Studiengang-URL
         */
        private MultilingualString $url,
        /**
         * Department/Institut (URL)
         */
        private MultilingualString $department,
        /**
         * Allgemeine Studienberatung
         * Shared property
         */
        private MultilingualLink $studentAdvice,
        /**
         * Beratung aus dem Fach
         */
        private MultilingualLink $subjectSpecificAdvice,
        /**
         * Beratungs- und Servicestellen der FAU
         * Shared property
         */
        private MultilingualLink $serviceCenters,
        /**
         * Infobroschüre Studiengang
         */
        private string $infoBrochure,
        /**
         * Semesterbeitrag
         * Shared property
         */
        private MultilingualLink $semesterFee,
        /**
         * Kostenpflichtig
         */
        private bool $feeRequired,
        /**
         * Studiengangsgebühren
         */
        private MultilingualString $degreeProgramFees,
        /**
         * Wege ins Ausland
         * Shared property
         */
        private MultilingualLink $abroadOpportunities,
        /**
         * Hinweise für internationale Bewerber
         * Shared property
         */
        private MultilingualLink $notesForInternationalApplicants,
        /**
         * StuVe/FSI
         * Shared property
         */
        private MultilingualLink $studentInitiatives,
        /**
         * Bewerben
         */
        private MultilingualLink $applyNowLink,
        //--- Degree program combinations --- //
        /**
         * Kombinationsmöglichkeiten
         */
        private DegreeProgramIds $combinations,
        /**
         * Eingeschränkt Kombinationsmöglichkeiten
         */
        private DegreeProgramIds $limitedCombinations,
        /**
         * CampoKeys
         */
        private CampoKeys $campoKeys,
    ) {

        $this->combinationsChangeset = IntegersListChangeset::new(
            $this->combinations->asArray(),
        );

        $this->limitedCombinationsChangeset = IntegersListChangeset::new(
            $this->limitedCombinations->asArray(),
        );
    }

    /**
     * @psalm-param DegreeProgramArrayType $data
     */
    public function updateDraft(
        array $data,
        DegreeProgramDataValidator $dataValidator,
        DegreeProgramSanitizer $contentSanitizer,
    ): void {

        $violations = $dataValidator->validateDraft($data);
        if ($violations->count() > 0) {
            throw new InvalidArgumentException('Invalid draft degree program data.');
        }

        $data = $this->sanitize($data, $contentSanitizer);

        $this->update($data);
    }

    /**
     * @psalm-param DegreeProgramArrayType $data
     */
    public function publish(
        array $data,
        DegreeProgramDataValidator $dataValidator,
        DegreeProgramSanitizer $contentSanitizer,
    ): void {

        $violations = $dataValidator->validatePublish($data);
        if ($violations->count() > 0) {
            throw new InvalidArgumentException('Invalid publish degree program data.');
        }

        $data = $this->sanitize($data, $contentSanitizer);

        $this->update($data);
    }

    /**
     * @psalm-param DegreeProgramArrayType $data
     * @psalm-return DegreeProgramArrayType $data
     */
    private function sanitize(array $data, DegreeProgramSanitizer $contentSanitizer): array
    {
        $data[self::CONTENT] = Content::mapDescriptions(
            $data[self::CONTENT],
            [$contentSanitizer, 'sanitizeContentField']
        );
        foreach (
            [
                self::CONTENT_RELATED_MASTER_REQUIREMENTS,
                self::DETAILS_AND_NOTES,
                self::LANGUAGE_SKILLS,
                self::ENTRY_TEXT,
            ] as $key
        ) {
            $data[$key] = MultilingualString::mapTranslations(
                $data[$key],
                [$contentSanitizer, 'sanitizeContentField']
            );
        }
        $data[self::LANGUAGE_SKILLS_HUMANITIES_FACULTY] = $contentSanitizer
            ->sanitizeContentField($data[self::LANGUAGE_SKILLS_HUMANITIES_FACULTY]);

        return $data;
    }

    /**
     * @psalm-param DegreeProgramArrayType $data
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    private function update(array $data): void
    {
        if ($data[self::ID] !== $this->id->asInt()) {
            throw new RuntimeException('Invalid entity id.');
        }

        $this->slug = MultilingualString::fromArray($data[self::SLUG]);
        $this->featuredImage = Image::fromArray($data[self::FEATURED_IMAGE]);
        $this->teaserImage = Image::fromArray($data[self::TEASER_IMAGE]);
        $this->title = MultilingualString::fromArray($data[self::TITLE]);
        $this->subtitle = MultilingualString::fromArray($data[self::SUBTITLE]);
        $this->standardDuration = $data[self::STANDARD_DURATION];
        $this->feeRequired = $data[self::FEE_REQUIRED];
        $this->start = MultilingualList::fromArray($data[self::START]);
        $this->numberOfStudents = NumberOfStudents::fromArray($data[self::NUMBER_OF_STUDENTS]);
        $this->teachingLanguage = MultilingualString::fromArray($data[self::TEACHING_LANGUAGE]);
        $this->attributes = MultilingualList::fromArray($data[self::ATTRIBUTES]);
        $this->degree = Degree::fromArray($data[self::DEGREE]);
        $this->faculty = MultilingualLinks::fromArray($data[self::FACULTY]);
        $this->location = MultilingualList::fromArray($data[self::LOCATION]);
        $this->subjectGroups = MultilingualList::fromArray($data[self::SUBJECT_GROUPS]);
        $this->videos = ArrayOfStrings::new(...$data[self::VIDEOS]);
        $this->metaDescription = MultilingualString::fromArray($data[self::META_DESCRIPTION]);
        $this->content = Content::fromArray($data[self::CONTENT]);
        $this->admissionRequirements = AdmissionRequirements::fromArray($data[self::ADMISSION_REQUIREMENTS]);
        $this->contentRelatedMasterRequirements = MultilingualString::fromArray($data[self::CONTENT_RELATED_MASTER_REQUIREMENTS]);
        $this->applicationDeadlineWinterSemester = $data[self::APPLICATION_DEADLINE_WINTER_SEMESTER];
        $this->applicationDeadlineSummerSemester = $data[self::APPLICATION_DEADLINE_SUMMER_SEMESTER];
        $this->detailsAndNotes = MultilingualString::fromArray($data[self::DETAILS_AND_NOTES]);
        $this->languageSkills = MultilingualString::fromArray($data[self::LANGUAGE_SKILLS]);
        $this->languageSkillsHumanitiesFaculty = $data[self::LANGUAGE_SKILLS_HUMANITIES_FACULTY];
        $this->germanLanguageSkillsForInternationalStudents = MultilingualLink::fromArray($data[self::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS]);
        $this->startOfSemester = MultilingualLink::fromArray($data[self::START_OF_SEMESTER]);
        $this->semesterDates = MultilingualLink::fromArray($data[self::SEMESTER_DATES]);
        $this->examinationsOffice = MultilingualLink::fromArray($data[self::EXAMINATIONS_OFFICE]);
        $this->examinationRegulations = $data[self::EXAMINATION_REGULATIONS];
        $this->moduleHandbook = $data[self::MODULE_HANDBOOK];
        $this->url = MultilingualString::fromArray($data[self::URL]);
        $this->department = MultilingualString::fromArray($data[self::DEPARTMENT]);
        $this->studentAdvice = MultilingualLink::fromArray($data[self::STUDENT_ADVICE]);
        $this->subjectSpecificAdvice = MultilingualLink::fromArray($data[self::SUBJECT_SPECIFIC_ADVICE]);
        $this->serviceCenters = MultilingualLink::fromArray($data[self::SERVICE_CENTERS]);
        $this->infoBrochure = $data[self::INFO_BROCHURE];
        $this->semesterFee = MultilingualLink::fromArray($data[self::SEMESTER_FEE]);
        $this->degreeProgramFees = MultilingualString::fromArray($data[self::DEGREE_PROGRAM_FEES]);
        $this->abroadOpportunities = MultilingualLink::fromArray($data[self::ABROAD_OPPORTUNITIES]);
        $this->keywords = MultilingualList::fromArray($data[self::KEYWORDS]);
        $this->areaOfStudy = MultilingualLinks::fromArray($data[self::AREA_OF_STUDY]);
        $this->combinations = DegreeProgramIds::fromArray($data[self::COMBINATIONS]);
        $this->limitedCombinations = DegreeProgramIds::fromArray($data[self::LIMITED_COMBINATIONS]);
        $this->notesForInternationalApplicants = MultilingualLink::fromArray($data[self::NOTES_FOR_INTERNATIONAL_APPLICANTS]);
        $this->studentInitiatives = MultilingualLink::fromArray($data[self::STUDENT_INITIATIVES]);
        $this->applyNowLink = MultilingualLink::fromArray($data[self::APPLY_NOW_LINK]);
        $this->entryText = MultilingualString::fromArray($data[self::ENTRY_TEXT]);
        $this->campoKeys = CampoKeys::fromArray($data[self::CAMPO_KEYS]);

        $this->combinationsChangeset = $this
            ->combinationsChangeset
            ->applyChanges($data[self::COMBINATIONS]);
        $this->limitedCombinationsChangeset = $this
            ->limitedCombinationsChangeset
            ->applyChanges($data[self::LIMITED_COMBINATIONS]);

        $this->events[] = DegreeProgramUpdated::new($this->id->asInt());
    }

    /**
     * @return array{
     *     id: DegreeProgramId,
     *     slug: MultilingualString,
     *     featured_image: Image,
     *     teaser_image: Image,
     *     title: MultilingualString,
     *     subtitle: MultilingualString,
     *     standard_duration: string,
     *     fee_required: bool,
     *     start: MultilingualList,
     *     number_of_students: NumberOfStudents,
     *     teaching_language: MultilingualString,
     *     attributes: MultilingualList,
     *     degree: Degree,
     *     faculty: MultilingualLinks,
     *     location: MultilingualList,
     *     subject_groups: MultilingualList,
     *     videos: ArrayOfStrings,
     *     meta_description: MultilingualString,
     *     content: Content,
     *     admission_requirements: AdmissionRequirements,
     *     content_related_master_requirements: MultilingualString,
     *     application_deadline_winter_semester: string,
     *     application_deadline_summer_semester: string,
     *     details_and_notes: MultilingualString,
     *     language_skills: MultilingualString,
     *     language_skills_humanities_faculty: string,
     *     german_language_skills_for_international_students: MultilingualLink,
     *     start_of_semester: MultilingualLink,
     *     semester_dates: MultilingualLink,
     *     examinations_office: MultilingualLink,
     *     examination_regulations: string,
     *     module_handbook: string,
     *     url: MultilingualString,
     *     department: MultilingualString,
     *     student_advice: MultilingualLink,
     *     subject_specific_advice: MultilingualLink,
     *     service_centers: MultilingualLink,
     *     info_brochure: string,
     *     semester_fee: MultilingualLink,
     *     degree_program_fees: MultilingualString,
     *     abroad_opportunities: MultilingualLink,
     *     keywords: MultilingualList,
     *     area_of_study: MultilingualLinks,
     *     combinations: DegreeProgramIds,
     *     limited_combinations: DegreeProgramIds,
     *     combinations_changeset: IntegersListChangeset,
     *     limited_combinations_changeset: IntegersListChangeset,
     *     notes_for_international_applicants: MultilingualLink,
     *     student_initiatives: MultilingualLink,
     *     apply_now_link: MultilingualLink,
     *     entry_text: MultilingualString,
     *     campo_keys: CampoKeys,
     * }
     * @internal Only for repositories usage
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function asArray(): array
    {
        return [
            self::ID => $this->id,
            self::SLUG => $this->slug,
            self::FEATURED_IMAGE => $this->featuredImage,
            self::TEASER_IMAGE => $this->teaserImage,
            self::TITLE => $this->title,
            self::SUBTITLE => $this->subtitle,
            self::STANDARD_DURATION => $this->standardDuration,
            self::FEE_REQUIRED => $this->feeRequired,
            self::START => $this->start,
            self::NUMBER_OF_STUDENTS => $this->numberOfStudents,
            self::TEACHING_LANGUAGE => $this->teachingLanguage,
            self::ATTRIBUTES => $this->attributes,
            self::DEGREE => $this->degree,
            self::FACULTY => $this->faculty,
            self::LOCATION => $this->location,
            self::SUBJECT_GROUPS => $this->subjectGroups,
            self::VIDEOS => $this->videos,
            self::META_DESCRIPTION => $this->metaDescription,
            self::CONTENT => $this->content,
            self::ADMISSION_REQUIREMENTS => $this->admissionRequirements,
            self::CONTENT_RELATED_MASTER_REQUIREMENTS => $this->contentRelatedMasterRequirements,
            self::APPLICATION_DEADLINE_WINTER_SEMESTER => $this->applicationDeadlineWinterSemester,
            self::APPLICATION_DEADLINE_SUMMER_SEMESTER => $this->applicationDeadlineSummerSemester,
            self::DETAILS_AND_NOTES => $this->detailsAndNotes,
            self::LANGUAGE_SKILLS => $this->languageSkills,
            self::LANGUAGE_SKILLS_HUMANITIES_FACULTY => $this->languageSkillsHumanitiesFaculty,
            self::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS =>
                $this->germanLanguageSkillsForInternationalStudents,
            self::START_OF_SEMESTER => $this->startOfSemester,
            self::SEMESTER_DATES => $this->semesterDates,
            self::EXAMINATIONS_OFFICE => $this->examinationsOffice,
            self::EXAMINATION_REGULATIONS => $this->examinationRegulations,
            self::MODULE_HANDBOOK => $this->moduleHandbook,
            self::URL => $this->url,
            self::DEPARTMENT => $this->department,
            self::STUDENT_ADVICE => $this->studentAdvice,
            self::SUBJECT_SPECIFIC_ADVICE => $this->subjectSpecificAdvice,
            self::SERVICE_CENTERS => $this->serviceCenters,
            self::INFO_BROCHURE => $this->infoBrochure,
            self::SEMESTER_FEE => $this->semesterFee,
            self::DEGREE_PROGRAM_FEES => $this->degreeProgramFees,
            self::ABROAD_OPPORTUNITIES => $this->abroadOpportunities,
            self::KEYWORDS => $this->keywords,
            self::AREA_OF_STUDY => $this->areaOfStudy,
            self::COMBINATIONS => $this->combinations,
            self::LIMITED_COMBINATIONS => $this->limitedCombinations,
            self::COMBINATIONS_CHANGESET => $this->combinationsChangeset,
            self::LIMITED_COMBINATIONS_CHANGESET => $this->limitedCombinationsChangeset,
            self::NOTES_FOR_INTERNATIONAL_APPLICANTS => $this->notesForInternationalApplicants,
            self::STUDENT_INITIATIVES => $this->studentInitiatives,
            self::APPLY_NOW_LINK => $this->applyNowLink,
            self::ENTRY_TEXT => $this->entryText,
            self::CAMPO_KEYS => $this->campoKeys,
        ];
    }

    /**
     * @return array<object>
     */
    public function releaseEvents(): array
    {
        return $this->events;
    }
}
