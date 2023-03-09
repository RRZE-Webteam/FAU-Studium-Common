<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Zugangsvoraussetzungen Master
 */
final class MasterDegreeAdmissionRequirementTaxonomy extends Taxonomy
{
    public const KEY = 'master_degree_adm_req';
    public const REST_BASE = 'admission-requirements/master';

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
            'Admission requirements Master’s degree',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Admission requirement Master’s degree',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return true;
    }
}
