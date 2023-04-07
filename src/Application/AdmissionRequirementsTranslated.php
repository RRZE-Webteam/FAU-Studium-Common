<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;

/**
 * @psalm-import-type AdmissionRequirementTranslatedType from AdmissionRequirementTranslated
 * @psalm-type AdmissionRequirementsTranslatedType = array{
 *     bachelor_or_teaching_degree?: AdmissionRequirementTranslatedType,
 *     teaching_degree_higher_semester?: AdmissionRequirementTranslatedType,
 *     master?: AdmissionRequirementTranslatedType,
 * }
 * // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
 * @psalm-type DegreeTypes = 'bachelor_or_teaching_degree' | 'teaching_degree_higher_semester' | 'master'
 */
final class AdmissionRequirementsTranslated
{
    /**
     * @psalm-param array<DegreeTypes, AdmissionRequirementTranslated> $requirements
     */
    private function __construct(
        private array $requirements
    ) {
    }

    /**
     * @psalm-param array<DegreeTypes, AdmissionRequirementTranslated> $admissionRequirements
     */
    public static function new(
        array $admissionRequirements,
    ): self {

        return new self(
            $admissionRequirements
        );
    }

    public static function fromAdmissionRequirements(
        AdmissionRequirements $admissionRequirements,
        string $languageCode
    ): self {

        $result = [];
        if (!$admissionRequirements->master()->isEmpty()) {
            $result[AdmissionRequirements::MASTER] = AdmissionRequirementTranslated::fromAdmissionRequirement(
                $admissionRequirements->master(),
                $languageCode,
            );
        }
        if (!$admissionRequirements->bachelorOrTeachingDegree()->isEmpty()) {
            $result[AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE] = AdmissionRequirementTranslated::fromAdmissionRequirement(
                $admissionRequirements->bachelorOrTeachingDegree(),
                $languageCode,
            );
        }
        if (!$admissionRequirements->teachingDegreeHigherSemester()->isEmpty()) {
            $result[AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER] = AdmissionRequirementTranslated::fromAdmissionRequirement(
                $admissionRequirements->teachingDegreeHigherSemester(),
                $languageCode,
            );
        }

        return new self(
            $result
        );
    }

    /**
     * @psalm-param AdmissionRequirementsTranslatedType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            array_map([AdmissionRequirementTranslated::class, 'fromArray'], $data)
        );
    }

    /**
     * @return AdmissionRequirementsTranslatedType
     */
    public function asArray(): array
    {
        return array_map(
            static fn(AdmissionRequirementTranslated $item) => $item->asArray(),
            $this->requirements
        );
    }

    /**
     * @psalm-return array<DegreeTypes, AdmissionRequirementTranslated>
     */
    public function requirements(): array
    {
        return $this->requirements;
    }

    public function mainLink(): ?AdmissionRequirementTranslated
    {
        return $this->requirements[AdmissionRequirements::MASTER]
            ?? $this->requirements[AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE]
            ?? $this->requirements[AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER]
            ?? null;
    }
}
