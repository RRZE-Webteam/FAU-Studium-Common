<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

/**
 * Studierendenzahl
 */
final class NumberOfStudentsTaxonomy extends Taxonomy
{
    public const KEY = 'number_of_students';
    public const REST_BASE = 'number-of-students';

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
            'Number of students',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'Number of students',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return false;
    }
}
