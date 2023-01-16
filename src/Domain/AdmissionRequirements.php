<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-import-type MultilingualLinkType from MultilingualLink
 * @psalm-type AdmissionRequirementsType = array{
 *     bachelor_or_teaching_degree: MultilingualLinkType,
 *     teaching_degree_higher_semester: MultilingualLinkType,
 *     master: MultilingualLinkType,
 * }
 */
final class AdmissionRequirements
{
    public const BACHELOR_OR_TEACHING_DEGREE = 'bachelor_or_teaching_degree';
    public const TEACHING_DEGREE_HIGHER_SEMESTER = 'teaching_degree_higher_semester';
    public const MASTER = 'master';

    private function __construct(
        /** Admission requirements for Bachelor’s/teaching degrees
         * (“Zugangsvoraussetzungen Bachelor/Lehramt”)
         */
        private MultilingualLink $bachelorOrTeachingDegree,
        /** Admission requirements for entering a teaching degree at a higher semester
         * (“Zugangsvoraussetzungen Lehramt höheres Semester”)
         */
        private MultilingualLink $teachingDegreeHigherSemester,
        /** Admission requirements for Master’s degree
         * (“Zugangsvoraussetzungen Master”)
         */
        private MultilingualLink $master,
    ) {
    }

    public static function new(
        MultilingualLink $bachelorOrTeachingDegree,
        MultilingualLink $teachingDegreeHigherSemester,
        MultilingualLink $master,
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
            MultilingualLink::fromArray($data[self::BACHELOR_OR_TEACHING_DEGREE]),
            MultilingualLink::fromArray($data[self::TEACHING_DEGREE_HIGHER_SEMESTER]),
            MultilingualLink::fromArray($data[self::MASTER]),
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

    public function requirementsForDegree(Degree $degree): MultilingualLink
    {
        $degreeName = $degree->name()->inEnglish(); //@TODO: maybe safer to use slugs here...
        if ($degreeName === 'Bachelor' || $degreeName === 'Teaching degree') {
            return $this->bachelorOrTeachingDegree;
        }

        if ($degreeName === 'Master') {
            return $this->master;
        }

        if ($degreeName !== 'frei') { //?
            return $this->teachingDegreeHigherSemester;
        }

        return MultilingualLink::empty();
    }

    public function bachelorOrTeachingDegree(): MultilingualLink
    {
        return $this->bachelorOrTeachingDegree;
    }

    public function teachingDegreeHigherSemester(): MultilingualLink
    {
        return $this->teachingDegreeHigherSemester;
    }

    public function master(): MultilingualLink
    {
        return $this->master;
    }
}
