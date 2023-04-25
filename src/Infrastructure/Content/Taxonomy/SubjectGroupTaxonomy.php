<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

use Fau\DegreeProgram\Common\Application\Filter\SubjectGroupFilter;

/**
 * Fächergruppen
 */
final class SubjectGroupTaxonomy extends Taxonomy
{
    public const KEY = 'faechergruppe';
    public const REST_BASE = SubjectGroupFilter::KEY;

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
            'Subject groups',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Subject group',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return false;
    }
}
