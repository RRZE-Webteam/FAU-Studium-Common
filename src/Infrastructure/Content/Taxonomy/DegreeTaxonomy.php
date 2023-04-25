<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

use Fau\DegreeProgram\Common\Application\Filter\DegreeFilter;

/**
 * Abschlüsse
 */
final class DegreeTaxonomy extends Taxonomy
{
    public const KEY = 'abschluss';
    public const REST_BASE = DegreeFilter::KEY;

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
            'Degrees',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Degree',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return true;
    }
}
