<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Zugangsvoraussetzungen Bachelor/Lehramt
 */
final class BachelorOrTeachingDegreeAdmissionRequirementTaxonomy extends Taxonomy
{
    public const KEY = 'bachelor_or_teaching_adm_req';
    public const REST_BASE = 'admission-requirements/bachelor-or-teaching-degree';

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
            "Admission requirements Bachelor's/Teaching degrees",
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            "Admission requirement Bachelor's/Teaching degrees",
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return true;
    }
}
