<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Zugangsvoraussetzungen Lehramt höheres Semester
 */
final class TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy extends Taxonomy
{
    public const KEY = 'teaching_higher_semester_adm_req';
    public const REST_BASE = 'admission-requirements/teaching-degree-higher-semester';

    public function key(): string
    {
        return self::KEY;
    }

    public function restBase(): string
    {
        return self::REST_BASE;
    }

    protected function pluralName(): string
    {
        return _x(
            'Admission requirements for entering a teaching degree at a higher semester',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Admission requirement for entering a teaching degree at a higher semester',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return true;
    }
}
