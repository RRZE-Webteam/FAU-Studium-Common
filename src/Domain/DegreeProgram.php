<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use Fau\DegreeProgram\Common\Domain\Event\DegreeProgramUpdated;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use Fau\DegreeProgram\Common\LanguageExtension\IntegersListChangeset;
use InvalidArgumentException;
use RuntimeException;

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
    public const STUDENT_REPRESENTATIVES = 'student_representatives';
    public const SEMESTER_FEE = 'semester_fee';
    public const DEGREE_PROGRAM_FEES = 'degree_program_fees';
    public const ABROAD_OPPORTUNITIES = 'abroad_opportunities';
    public const KEYWORDS = 'keywords';
    public const AREA_OF_STUDY = 'area_of_study';
    public const COMBINATIONS = 'combinations';
    public const LIMITED_COMBINATIONS = 'limited_combinations';
    public const COMBINATIONS_CHANGESET = 'combinations_changeset';
    public const LIMITED_COMBINATIONS_CHANGESET = 'limited_combinations_changeset';

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
         * Number of semesters
         * Regelstudienzeit
         */
        private int $standardDuration,
        /**
         * Kostenpflichtig
         */
        private bool $feeRequired,
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
        //--- Content (“Inhalte”) ---//
        private Content $content,
        //--- Admission requirements, application and enrollment (“Zugangsvoraussetzungen, Bewerbung und Einschreibung”) ---//
        /**
         * Bachelor’s/teaching degrees, teaching degree at a higher semester, Master’s degree
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
         */
        private MultilingualLink $startOfSemester,
        /**
         * Semestertermine
         */
        private MultilingualLink $semesterDates,
        /**
         * Prüfungsamt
         */
        private MultilingualLink $examinationsOffice,
        /**
         * Studien- und Prüfungsordnung
         */
        private MultilingualString $examinationRegulations,
        /**
         * Modulhandbuch
         */
        private string $moduleHandbook,
        /**
         * Studiengang-URL
         */
        private MultilingualString $url,
        private MultilingualLink $department,
        /**
         * Allgemeine Studienberatung
         */
        private MultilingualLink $studentAdvice,
        /**
         * Beratung aus dem Fach
         */
        private MultilingualLink $subjectSpecificAdvice,
        /**
         * Beratungs- und Servicestellen der FAU
         */
        private MultilingualLink $serviceCenters,
        /**
         * Studierendenvertretung/FSI
         */
        private string $studentRepresentatives,
        /**
         * Semesterbeitrag
         */
        private MultilingualLink $semesterFee,
        /**
         * Studiengangsgebühren
         */
        private MultilingualString $degreeProgramFees,
        /**
         * Wege ins Ausland
         */
        private MultilingualLink $abroadOpportunities,
        //--- Properties for filtering --- //
        /**
         * Schlagworte
         */
        private MultilingualList $keywords,
        /**
         * Studienbereich
         */
        private MultilingualLinks $areaOfStudy,
        //--- Degree program combinations --- //
        /**
         * Kombinationsmöglichkeiten
         */
        private DegreeProgramIds $combinations,
        /**
         * Eingeschränkt Kombinationsmöglichkeiten
         */
        private DegreeProgramIds $limitedCombinations,
    ) {

        $this->combinationsChangeset = IntegersListChangeset::new(
            $this->combinations->asArray(),
        );

        $this->limitedCombinationsChangeset = IntegersListChangeset::new(
            $this->limitedCombinations->asArray(),
        );
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     * @psalm-suppress MixedArgument
     */
    public function update(
        array $data,
        DegreeProgramDataValidator $dataValidator,
        DegreeProgramSanitizer $contentSanitizer,
    ): void {

        $violations = $dataValidator->validate($data);
        if (count($violations) > 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid degree program data. Violations: %s.',
                    implode('|', $violations->getArrayCopy())
                )
            );
        }

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
        $this->content = Content::fromArray($data[self::CONTENT])
            ->mapDescriptions([$contentSanitizer, 'sanitizeContentField']);
        $this->admissionRequirements = AdmissionRequirements::fromArray($data[self::ADMISSION_REQUIREMENTS]);
        $this->contentRelatedMasterRequirements = MultilingualString::fromArray($data[self::CONTENT_RELATED_MASTER_REQUIREMENTS])
            ->mapTranslations([$contentSanitizer, 'sanitizeContentField']);
        $this->applicationDeadlineWinterSemester = $contentSanitizer->sanitizeContentField($data[self::APPLICATION_DEADLINE_WINTER_SEMESTER]);
        $this->applicationDeadlineSummerSemester = $contentSanitizer->sanitizeContentField($data[self::APPLICATION_DEADLINE_SUMMER_SEMESTER]);
        $this->detailsAndNotes = MultilingualString::fromArray($data[self::DETAILS_AND_NOTES])
            ->mapTranslations([$contentSanitizer, 'sanitizeContentField']);
        $this->languageSkills = MultilingualString::fromArray($data[self::LANGUAGE_SKILLS])
            ->mapTranslations([$contentSanitizer, 'sanitizeContentField']);
        $this->languageSkillsHumanitiesFaculty = $contentSanitizer->sanitizeContentField($data[self::LANGUAGE_SKILLS_HUMANITIES_FACULTY]);
        $this->germanLanguageSkillsForInternationalStudents = MultilingualLink::fromArray($data[self::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS]);
        $this->startOfSemester = MultilingualLink::fromArray($data[self::START_OF_SEMESTER]);
        $this->semesterDates = MultilingualLink::fromArray($data[self::SEMESTER_DATES]);
        $this->examinationsOffice = MultilingualLink::fromArray($data[self::EXAMINATIONS_OFFICE]);
        $this->examinationRegulations = MultilingualString::fromArray($data[self::EXAMINATION_REGULATIONS]);
        $this->moduleHandbook = $data[self::MODULE_HANDBOOK];
        $this->url = MultilingualString::fromArray($data[self::URL]);
        $this->department = MultilingualLink::fromArray($data[self::DEPARTMENT]);
        $this->studentAdvice = MultilingualLink::fromArray($data[self::STUDENT_ADVICE]);
        $this->subjectSpecificAdvice = MultilingualLink::fromArray($data[self::SUBJECT_SPECIFIC_ADVICE]);
        $this->serviceCenters = MultilingualLink::fromArray($data[self::SERVICE_CENTERS]);
        $this->studentRepresentatives = $data[self::STUDENT_REPRESENTATIVES];
        $this->semesterFee = MultilingualLink::fromArray($data[self::SEMESTER_FEE]);
        $this->degreeProgramFees = MultilingualString::fromArray($data[self::DEGREE_PROGRAM_FEES]);
        $this->abroadOpportunities = MultilingualLink::fromArray($data[self::ABROAD_OPPORTUNITIES]);
        $this->keywords = MultilingualList::fromArray($data[self::KEYWORDS]);
        $this->areaOfStudy = MultilingualLinks::fromArray($data[self::AREA_OF_STUDY]);
        $this->combinations = DegreeProgramIds::fromArray($data[self::COMBINATIONS]);
        $this->limitedCombinations = DegreeProgramIds::fromArray($data[self::LIMITED_COMBINATIONS]);

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
     *     standard_duration: int,
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
     *     examination_regulations: MultilingualString,
     *     module_handbook: string,
     *     url: MultilingualString,
     *     department: MultilingualLink,
     *     student_advice: MultilingualLink,
     *     subject_specific_advice: MultilingualLink,
     *     service_centers: MultilingualLink,
     *     student_representatives: string,
     *     semester_fee: MultilingualLink,
     *     degree_program_fees: MultilingualString,
     *     abroad_opportunities: MultilingualLink,
     *     keywords: MultilingualList,
     *     area_of_study: MultilingualLinks,
     *     combinations: DegreeProgramIds,
     *     limited_combinations: DegreeProgramIds,
     *     combinations_changeset: IntegersListChangeset,
     *     limited_combinations_changeset: IntegersListChangeset,
     * }
     * @internal Only for repositories usage
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
            self::STUDENT_REPRESENTATIVES => $this->studentRepresentatives,
            self::SEMESTER_FEE => $this->semesterFee,
            self::DEGREE_PROGRAM_FEES => $this->degreeProgramFees,
            self::ABROAD_OPPORTUNITIES => $this->abroadOpportunities,
            self::KEYWORDS => $this->keywords,
            self::AREA_OF_STUDY => $this->areaOfStudy,
            self::COMBINATIONS => $this->combinations,
            self::LIMITED_COMBINATIONS => $this->limitedCombinations,
            self::COMBINATIONS_CHANGESET => $this->combinationsChangeset,
            self::LIMITED_COMBINATIONS_CHANGESET => $this->limitedCombinationsChangeset,
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
