<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Beratung aus dem Fach
 */
final class SubjectSpecificAdviceTaxonomy extends Taxonomy
{
    public const KEY = 'subject_specific_advice';
    public const REST_BASE = 'subject-specific-advice';

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
            'Subject-specific advices',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Subject-specific advice',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return false;
    }
}
