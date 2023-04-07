<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;

/**
 * @psalm-import-type AdmissionRequirementsType from AdmissionRequirements
 */
final class ConditionalFieldsFilter
{
    public const FACULTY_PHILOSOPHY = 'phil';
    public const FACULTY_NATURAL_SCIENCES = 'nat';
    public const SEMESTER_SUMMER = 'Sommersemester';
    public const SEMESTER_WINTER = 'Wintersemester';
    public const ADMISSION_REQUIREMENT_FREE = 'frei';
    public const ADDITIONAL_DEGREE_NAME = 'Weiteres';
    public const DEGREE_ABBREVIATION_GERMAN_BACHELOR = 'BA';
    public const DEGREE_ABBREVIATION_GERMAN_MASTERS = 'MA';
    public const DEGREE_ABBREVIATION_GERMAN_TEACHING_DEGREE = 'LA';
    public const ALLOWED_FACULTY_SLUGS_FOR_COMBINATION = [self::FACULTY_PHILOSOPHY, self::FACULTY_NATURAL_SCIENCES];

    public function filter(DegreeProgramViewRaw $raw, ArrayOfStrings $facultySlugs): DegreeProgramViewRaw
    {
        $data = $raw->asArray();

        if (!$raw->isFeeRequired()) {
            $data[DegreeProgram::DEGREE_PROGRAM_FEES] = MultilingualString::empty()->asArray();
        }

        if (!$this->isCombinationsEnabled($facultySlugs, $raw->degree())) {
            $data[DegreeProgram::COMBINATIONS] = [];
            $data[DegreeProgram::LIMITED_COMBINATIONS] = [];
        }

        $data[DegreeProgram::ADMISSION_REQUIREMENTS] = $this->filterAdmissionRequirements(
            $raw->admissionRequirements(),
            $raw->degree()
        );

        if (!$this->isLanguageSkillForFacultyOfHumanitiesOnlyEnabled($facultySlugs, $raw->degree())) {
            $data[DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY] = '';
        }

        if (!$raw->start()->containGermanString(self::SEMESTER_WINTER)) {
            $data[DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER] = '';
        }

        if (!$raw->start()->containGermanString(self::SEMESTER_SUMMER)) {
            $data[DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER] = '';
        }

        return DegreeProgramViewRaw::fromArray($data);
    }

    private function isBachelorContext(Degree $degree): bool
    {
        return (
            $degree->hasGermanAbbreviation(self::DEGREE_ABBREVIATION_GERMAN_BACHELOR)
            || $degree->name()->inGerman() === self::ADDITIONAL_DEGREE_NAME
        );
    }

    private function isTeachingDegreeContext(Degree $degree): bool
    {
        return $degree->hasGermanAbbreviation(self::DEGREE_ABBREVIATION_GERMAN_TEACHING_DEGREE);
    }

    private function isMasterContext(Degree $degree): bool
    {
        return $degree->hasGermanAbbreviation(self::DEGREE_ABBREVIATION_GERMAN_MASTERS);
    }

    private function isBachelorOrTeachingDegreeContext(Degree $degree): bool
    {
        return $this->isBachelorContext($degree) || $this->isTeachingDegreeContext($degree);
    }

    /**
     * @return AdmissionRequirementsType
     */
    private function filterAdmissionRequirements(
        AdmissionRequirements $admissionRequirements,
        Degree $degree
    ): array {

        $result = $admissionRequirements->asArray();
        $empty = AdmissionRequirement::empty()->asArray();

        if (!$this->isMasterContext($degree)) {
            $result[AdmissionRequirements::MASTER] = $empty;
        }

        if (!$this->isBachelorOrTeachingDegreeContext($degree)) {
            $result[AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE] = $empty;
            $result[AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER] = $empty;
            return $result;
        }

        if (
            $admissionRequirements
            ->bachelorOrTeachingDegree()
            ->hasGermanName(self::ADMISSION_REQUIREMENT_FREE)
        ) {
            $result[AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER] = $empty;
        }

        return $result;
    }

    private function isCombinationsEnabled(ArrayOfStrings $facultySlugs, Degree $degree): bool
    {
        return array_intersect(
            $facultySlugs->getArrayCopy(),
            self::ALLOWED_FACULTY_SLUGS_FOR_COMBINATION
        ) && $this->isBachelorContext($degree);
    }

    private function isLanguageSkillForFacultyOfHumanitiesOnlyEnabled(
        ArrayOfStrings $facultySlugs,
        Degree $degree
    ): bool {

        return in_array(
            self::FACULTY_PHILOSOPHY,
            $facultySlugs->getArrayCopy(),
            true
        ) && $this->isBachelorOrTeachingDegreeContext($degree);
    }
}
