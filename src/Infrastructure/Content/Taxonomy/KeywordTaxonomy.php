<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Schlagworte
 */
final class KeywordTaxonomy extends Taxonomy
{
    public const KEY = 'keyword';
    public const REST_BASE = 'keyword';

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
            'Keywords',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Keyword',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return false;
    }
}
