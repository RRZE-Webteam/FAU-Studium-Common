<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use JsonSerializable;

final class DegreeProgramViewTranslated implements JsonSerializable
{
    public const APPLICATION = 'application';

    public function __construct(
        private DegreeProgramId $id,
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
        private string $degree,
        private Link $faculty,
        private string $location,
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
        private Link $examinationRegulations,
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
        private RelatedDegreePrograms $combinations,
        private RelatedDegreePrograms $limitedCombinations,
    ) {
    }

    public function asArray(): array
    {
        return [
            DegreeProgram::ID => $this->id->asInt(),
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
            DegreeProgram::DEGREE => $this->degree,
            DegreeProgram::FACULTY => $this->faculty->asArray(),
            DegreeProgram::LOCATION => $this->location,
            DegreeProgram::SUBJECT_GROUPS => $this->subjectGroups->getArrayCopy(),
            DegreeProgram::VIDEOS => $this->videos->getArrayCopy(),
            DegreeProgram::META_DESCRIPTION => $this->metaDescription,
            DegreeProgram::CONTENT => $this->content,
            self::APPLICATION => $this->application,
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
            DegreeProgram::EXAMINATION_REGULATIONS => $this->examinationRegulations->asArray(),
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
            DegreeProgram::COMBINATIONS => $this->combinations->asArray(),
            DegreeProgram::LIMITED_COMBINATIONS => $this->limitedCombinations->asArray(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }
}
