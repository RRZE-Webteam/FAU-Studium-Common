<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\DegreeProgramIds;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use JsonSerializable;

/**
 * @psalm-import-type MultilingualStringType from MultilingualString
 * @psalm-import-type MultilingualLinkType from MultilingualLink
 * @psalm-import-type ContentType from Content
 * @psalm-import-type AdmissionRequirementsType from AdmissionRequirements
 * @psalm-type DegreeProgramViewRawArrayType = array {
 *     id: int,
 *     slug: MultilingualStringType,
 *     featured_image: array{id: int, url: string},
 *     teaser_image: array{id: int, url: string},
 *     title: MultilingualStringType,
 *     subtitle: MultilingualStringType,
 *     standard_duration: int,
 *     fee_required: bool,
 *     start: array<MultilingualStringType>,
 *     number_of_students: array{id: string, description: string},
 *     teaching_language: MultilingualStringType,
 *     attributes: array<MultilingualStringType>,
 *     degree: array{
 *          id: string,
 *          name: MultilingualStringType,
 *          abbreviation: MultilingualStringType
 *     },
 *     faculty: MultilingualLinkType,
 *     location: MultilingualStringType,
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
 *     examination_regulations: MultilingualLinkType,
 *     module_handbook: string,
 *     url: MultilingualStringType,
 *     department: MultilingualLinkType,
 *     student_advice: MultilingualLinkType,
 *     subject_specific_advice: MultilingualLinkType,
 *     service_centers: MultilingualLinkType,
 *     student_representatives: string,
 *     semester_fee: MultilingualLinkType,
 *     degree_program_fees: MultilingualStringType,
 *     abroad_opportunities: MultilingualLinkType,
 *     keywords: array<MultilingualStringType>,
 *     area_of_study: array<MultilingualLinkType>,
 *     combinations: array<int>,
 *     limited_combinations: array<int>,
 * }
 */
final class DegreeProgramViewRaw implements JsonSerializable
{
    private function __construct(
        private DegreeProgramId $id,
        private MultilingualString $slug,
        private Image $featuredImage,
        private Image $teaserImage,
        private MultilingualString $title,
        private MultilingualString $subtitle,
        private int $standardDuration,
        private bool $feeRequired,
        private MultilingualList $start,
        private NumberOfStudents $numberOfStudents,
        private MultilingualString $teachingLanguage,
        private MultilingualList $attributes,
        private Degree $degree,
        private MultilingualLink $faculty,
        private MultilingualString $location,
        private MultilingualList $subjectGroups,
        private ArrayOfStrings $videos,
        private MultilingualString $metaDescription,
        private Content $content,
        private AdmissionRequirements $admissionRequirements,
        private MultilingualString $contentRelatedMasterRequirements,
        private string $applicationDeadlineWinterSemester,
        private string $applicationDeadlineSummerSemester,
        private MultilingualString $detailsAndNotes,
        private MultilingualString $languageSkills,
        private string $languageSkillsHumanitiesFaculty,
        private MultilingualLink $germanLanguageSkillsForInternationalStudents,
        private MultilingualLink $startOfSemester,
        private MultilingualLink $semesterDates,
        private MultilingualLink $examinationsOffice,
        private MultilingualLink $examinationRegulations,
        private string $moduleHandbook,
        private MultilingualString $url,
        private MultilingualLink $department,
        private MultilingualLink $studentAdvice,
        private MultilingualLink $subjectSpecificAdvice,
        private MultilingualLink $serviceCenters,
        private string $studentRepresentatives,
        private MultilingualLink $semesterFee,
        private MultilingualString $degreeProgramFees,
        private MultilingualLink $abroadOpportunities,
        private MultilingualList $keywords,
        private MultilingualLinks $areaOfStudy,
        private DegreeProgramIds $combinations,
        private DegreeProgramIds $limitedCombinations,
    ) {
    }

    public static function fromDegreeProgram(DegreeProgram $degreeProgram): self
    {
        $data = $degreeProgram->asArray();
        return new self(
            $data[DegreeProgram::ID],
            $data[DegreeProgram::SLUG],
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

    /**
     *
     * @psalm-param DegreeProgramViewRawArrayType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: DegreeProgramId::fromInt($data[DegreeProgram::ID]),
            slug: MultilingualString::fromArray($data[DegreeProgram::SLUG]),
            featuredImage: Image::fromArray($data[DegreeProgram::FEATURED_IMAGE]),
            teaserImage: Image::fromArray($data[DegreeProgram::TEASER_IMAGE]),
            title: MultilingualString::fromArray($data[DegreeProgram::TITLE]),
            subtitle: MultilingualString::fromArray($data[DegreeProgram::SUBTITLE]),
            standardDuration: $data[DegreeProgram::STANDARD_DURATION],
            feeRequired: $data[DegreeProgram::FEE_REQUIRED],
            start: MultilingualList::fromArray($data[DegreeProgram::START]),
            numberOfStudents: NumberOfStudents::fromArray($data[DegreeProgram::NUMBER_OF_STUDENTS]),
            teachingLanguage: MultilingualString::fromArray($data[DegreeProgram::TEACHING_LANGUAGE]),
            attributes: MultilingualList::fromArray($data[DegreeProgram::ATTRIBUTES]),
            degree: Degree::fromArray($data[DegreeProgram::DEGREE]),
            faculty: MultilingualLink::fromArray($data[DegreeProgram::FACULTY]),
            location: MultilingualString::fromArray($data[DegreeProgram::LOCATION]),
            subjectGroups: MultilingualList::fromArray($data[DegreeProgram::SUBJECT_GROUPS]),
            videos: ArrayOfStrings::new(...$data[DegreeProgram::VIDEOS]),
            metaDescription: MultilingualString::fromArray($data[DegreeProgram::META_DESCRIPTION]),
            content: Content::fromArray($data[DegreeProgram::CONTENT]),
            admissionRequirements: AdmissionRequirements::fromArray($data[DegreeProgram::ADMISSION_REQUIREMENTS]),
            contentRelatedMasterRequirements: MultilingualString::fromArray($data[DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS]),
            applicationDeadlineWinterSemester: $data[DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER],
            applicationDeadlineSummerSemester: $data[DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER],
            detailsAndNotes: MultilingualString::fromArray($data[DegreeProgram::DETAILS_AND_NOTES]),
            languageSkills: MultilingualString::fromArray($data[DegreeProgram::LANGUAGE_SKILLS]),
            languageSkillsHumanitiesFaculty: $data[DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY],
            germanLanguageSkillsForInternationalStudents: MultilingualLink::fromArray($data[DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS]),
            startOfSemester: MultilingualLink::fromArray($data[DegreeProgram::START_OF_SEMESTER]),
            semesterDates: MultilingualLink::fromArray($data[DegreeProgram::SEMESTER_DATES]),
            examinationsOffice: MultilingualLink::fromArray($data[DegreeProgram::EXAMINATIONS_OFFICE]),
            examinationRegulations: MultilingualLink::fromArray($data[DegreeProgram::EXAMINATION_REGULATIONS]),
            moduleHandbook: $data[DegreeProgram::MODULE_HANDBOOK],
            url: MultilingualString::fromArray($data[DegreeProgram::URL]),
            department: MultilingualLink::fromArray($data[DegreeProgram::DEPARTMENT]),
            studentAdvice: MultilingualLink::fromArray($data[DegreeProgram::STUDENT_ADVICE]),
            subjectSpecificAdvice: MultilingualLink::fromArray($data[DegreeProgram::SUBJECT_SPECIFIC_ADVICE]),
            serviceCenters: MultilingualLink::fromArray($data[DegreeProgram::SERVICE_CENTERS]),
            studentRepresentatives: $data[DegreeProgram::STUDENT_REPRESENTATIVES],
            semesterFee: MultilingualLink::fromArray($data[DegreeProgram::SEMESTER_FEE]),
            degreeProgramFees: MultilingualString::fromArray($data[DegreeProgram::DEGREE_PROGRAM_FEES]),
            abroadOpportunities: MultilingualLink::fromArray($data[DegreeProgram::ABROAD_OPPORTUNITIES]),
            keywords: MultilingualList::fromArray($data[DegreeProgram::KEYWORDS]),
            areaOfStudy: MultilingualLinks::fromArray($data[DegreeProgram::AREA_OF_STUDY]),
            combinations: DegreeProgramIds::fromArray($data[DegreeProgram::COMBINATIONS]),
            limitedCombinations: DegreeProgramIds::fromArray($data[DegreeProgram::LIMITED_COMBINATIONS]),
        );
    }

    /**
     * @psalm-return DegreeProgramViewRawArrayType
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function asArray(): array
    {
        return [
            DegreeProgram::ID => $this->id->asInt(),
            DegreeProgram::SLUG => $this->slug->asArray(),
            DegreeProgram::FEATURED_IMAGE => $this->featuredImage->asArray(),
            DegreeProgram::TEASER_IMAGE => $this->teaserImage->asArray(),
            DegreeProgram::TITLE => $this->title->asArray(),
            DegreeProgram::SUBTITLE => $this->subtitle->asArray(),
            DegreeProgram::STANDARD_DURATION => $this->standardDuration,
            DegreeProgram::FEE_REQUIRED => $this->feeRequired,
            DegreeProgram::START => $this->start->asArray(),
            DegreeProgram::NUMBER_OF_STUDENTS => $this->numberOfStudents->asArray(),
            DegreeProgram::TEACHING_LANGUAGE => $this->teachingLanguage->asArray(),
            DegreeProgram::ATTRIBUTES => $this->attributes->asArray(),
            DegreeProgram::DEGREE => $this->degree->asArray(),
            DegreeProgram::FACULTY => $this->faculty->asArray(),
            DegreeProgram::LOCATION => $this->location->asArray(),
            DegreeProgram::SUBJECT_GROUPS => $this->subjectGroups->asArray(),
            DegreeProgram::VIDEOS => $this->videos->getArrayCopy(),
            DegreeProgram::META_DESCRIPTION => $this->metaDescription->asArray(),
            DegreeProgram::CONTENT => $this->content->asArray(),
            DegreeProgram::ADMISSION_REQUIREMENTS => $this->admissionRequirements->asArray(),
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS =>
                $this->contentRelatedMasterRequirements->asArray(),
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER => $this->applicationDeadlineWinterSemester,
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => $this->applicationDeadlineSummerSemester,
            DegreeProgram::DETAILS_AND_NOTES => $this->detailsAndNotes->asArray(),
            DegreeProgram::LANGUAGE_SKILLS => $this->languageSkills->asArray(),
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY =>
                $this->languageSkillsHumanitiesFaculty,
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS =>
                $this->germanLanguageSkillsForInternationalStudents->asArray(),
            DegreeProgram::START_OF_SEMESTER => $this->startOfSemester->asArray(),
            DegreeProgram::SEMESTER_DATES => $this->semesterDates->asArray(),
            DegreeProgram::EXAMINATIONS_OFFICE => $this->examinationsOffice->asArray(),
            DegreeProgram::EXAMINATION_REGULATIONS => $this->examinationRegulations->asArray(),
            DegreeProgram::MODULE_HANDBOOK => $this->moduleHandbook,
            DegreeProgram::URL => $this->url->asArray(),
            DegreeProgram::DEPARTMENT => $this->department->asArray(),
            DegreeProgram::STUDENT_ADVICE => $this->studentAdvice->asArray(),
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => $this->subjectSpecificAdvice->asArray(),
            DegreeProgram::SERVICE_CENTERS => $this->serviceCenters->asArray(),
            DegreeProgram::STUDENT_REPRESENTATIVES => $this->studentRepresentatives,
            DegreeProgram::SEMESTER_FEE => $this->semesterFee->asArray(),
            DegreeProgram::DEGREE_PROGRAM_FEES => $this->degreeProgramFees->asArray(),
            DegreeProgram::ABROAD_OPPORTUNITIES => $this->abroadOpportunities->asArray(),
            DegreeProgram::KEYWORDS => $this->keywords->asArray(),
            DegreeProgram::AREA_OF_STUDY => $this->areaOfStudy->asArray(),
            DegreeProgram::COMBINATIONS => $this->combinations->asArray(),
            DegreeProgram::LIMITED_COMBINATIONS => $this->limitedCombinations->asArray(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }

    public function id(): DegreeProgramId
    {
        return $this->id;
    }

    public function slug(): MultilingualString
    {
        return $this->slug;
    }

    public function featuredImage(): Image
    {
        return $this->featuredImage;
    }

    public function teaserImage(): Image
    {
        return $this->teaserImage;
    }

    public function title(): MultilingualString
    {
        return $this->title;
    }

    public function subtitle(): MultilingualString
    {
        return $this->subtitle;
    }

    public function standardDuration(): int
    {
        return $this->standardDuration;
    }

    public function isFeeRequired(): bool
    {
        return $this->feeRequired;
    }

    public function start(): MultilingualList
    {
        return $this->start;
    }

    public function numberOfStudents(): NumberOfStudents
    {
        return $this->numberOfStudents;
    }

    public function teachingLanguage(): MultilingualString
    {
        return $this->teachingLanguage;
    }

    public function attributes(): MultilingualList
    {
        return $this->attributes;
    }

    public function degree(): Degree
    {
        return $this->degree;
    }

    public function faculty(): MultilingualLink
    {
        return $this->faculty;
    }

    public function location(): MultilingualString
    {
        return $this->location;
    }

    public function subjectGroups(): MultilingualList
    {
        return $this->subjectGroups;
    }

    public function videos(): ArrayOfStrings
    {
        return $this->videos;
    }

    public function metaDescription(): MultilingualString
    {
        return $this->metaDescription;
    }

    public function content(): Content
    {
        return $this->content;
    }

    public function admissionRequirements(): AdmissionRequirements
    {
        return $this->admissionRequirements;
    }

    public function contentRelatedMasterRequirements(): MultilingualString
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

    public function detailsAndNotes(): MultilingualString
    {
        return $this->detailsAndNotes;
    }

    public function languageSkills(): MultilingualString
    {
        return $this->languageSkills;
    }

    public function languageSkillsHumanitiesFaculty(): string
    {
        return $this->languageSkillsHumanitiesFaculty;
    }

    public function germanLanguageSkillsForInternationalStudents(): MultilingualLink
    {
        return $this->germanLanguageSkillsForInternationalStudents;
    }

    public function startOfSemester(): MultilingualLink
    {
        return $this->startOfSemester;
    }

    public function semesterDates(): MultilingualLink
    {
        return $this->semesterDates;
    }

    public function examinationsOffice(): MultilingualLink
    {
        return $this->examinationsOffice;
    }

    public function examinationRegulations(): MultilingualLink
    {
        return $this->examinationRegulations;
    }

    public function moduleHandbook(): string
    {
        return $this->moduleHandbook;
    }

    public function url(): MultilingualString
    {
        return $this->url;
    }

    public function department(): MultilingualLink
    {
        return $this->department;
    }

    public function studentAdvice(): MultilingualLink
    {
        return $this->studentAdvice;
    }

    public function subjectSpecificAdvice(): MultilingualLink
    {
        return $this->subjectSpecificAdvice;
    }

    public function serviceCenters(): MultilingualLink
    {
        return $this->serviceCenters;
    }

    public function studentRepresentatives(): string
    {
        return $this->studentRepresentatives;
    }

    public function semesterFee(): MultilingualLink
    {
        return $this->semesterFee;
    }

    public function degreeProgramFees(): MultilingualString
    {
        return $this->degreeProgramFees;
    }

    public function abroadOpportunities(): MultilingualLink
    {
        return $this->abroadOpportunities;
    }

    public function keywords(): MultilingualList
    {
        return $this->keywords;
    }

    public function areaOfStudy(): MultilingualLinks
    {
        return $this->areaOfStudy;
    }

    public function combinations(): DegreeProgramIds
    {
        return $this->combinations;
    }

    public function limitedCombinations(): DegreeProgramIds
    {
        return $this->limitedCombinations;
    }
}
