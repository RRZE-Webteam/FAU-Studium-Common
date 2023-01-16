<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Studien- und Prüfungsordnungen
 */
final class ExaminationRegulationsTaxonomy extends Taxonomy
{
    public const KEY = 'examination_regulation';
    public const REST_BASE = 'examination-regulation';

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
            'Degree program and examination regulations',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Degree program and examination regulations',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return false;
    }
}
