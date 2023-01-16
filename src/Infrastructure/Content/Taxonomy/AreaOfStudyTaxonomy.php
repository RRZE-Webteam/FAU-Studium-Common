<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Studienbereich
 */
final class AreaOfStudyTaxonomy extends Taxonomy
{
    public const KEY = 'area_of_study';
    public const REST_BASE = 'area-of-study';

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
            'Areas of study',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Area of study',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return false;
    }
}
