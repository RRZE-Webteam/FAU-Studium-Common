<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-import-type AdmissionRequirementType from AdmissionRequirement
 * @psalm-type AdmissionRequirementsType = array{
 *     bachelor_or_teaching_degree: AdmissionRequirementType,
 *     teaching_degree_higher_semester: AdmissionRequirementType,
 *     master: AdmissionRequirementType,
 * }
 */
final class AdmissionRequirements
{
    public const BACHELOR_OR_TEACHING_DEGREE = 'bachelor_or_teaching_degree';
    public const TEACHING_DEGREE_HIGHER_SEMESTER = 'teaching_degree_higher_semester';
    public const MASTER = 'master';

    private function __construct(
        /** Admission requirements for Bachelor's/teaching degrees
         * (“Zugangsvoraussetzungen Bachelor/Lehramt”)
         */
        private AdmissionRequirement $bachelorOrTeachingDegree,
        /** Admission requirements for entering a Bachelor's/teaching degree at a higher semester
         * (“Zugangsvoraussetzungen Lehramt höheres Semester”)
         */
        private AdmissionRequirement $teachingDegreeHigherSemester,
        /** Admission requirements for Master’s degree
         * (“Zugangsvoraussetzungen Master”)
         */
        private AdmissionRequirement $master,
    ) {
    }

    public static function new(
        AdmissionRequirement $bachelorOrTeachingDegree,
        AdmissionRequirement $teachingDegreeHigherSemester,
        AdmissionRequirement $master,
    ): self {

        return new self(
            $bachelorOrTeachingDegree,
            $teachingDegreeHigherSemester,
            $master
        );
    }

    /**
     * @psalm-param AdmissionRequirementsType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            AdmissionRequirement::fromArray($data[self::BACHELOR_OR_TEACHING_DEGREE]),
            AdmissionRequirement::fromArray($data[self::TEACHING_DEGREE_HIGHER_SEMESTER]),
            AdmissionRequirement::fromArray($data[self::MASTER]),
        );
    }

    /**
     * @return AdmissionRequirementsType
     */
    public function asArray(): array
    {
        return [
            self::BACHELOR_OR_TEACHING_DEGREE => $this->bachelorOrTeachingDegree->asArray(),
            self::TEACHING_DEGREE_HIGHER_SEMESTER => $this->teachingDegreeHigherSemester->asArray(),
            self::MASTER => $this->master->asArray(),
        ];
    }

    public function bachelorOrTeachingDegree(): AdmissionRequirement
    {
        return $this->bachelorOrTeachingDegree;
    }

    public function teachingDegreeHigherSemester(): AdmissionRequirement
    {
        return $this->teachingDegreeHigherSemester;
    }

    public function master(): AdmissionRequirement
    {
        return $this->master;
    }
}
