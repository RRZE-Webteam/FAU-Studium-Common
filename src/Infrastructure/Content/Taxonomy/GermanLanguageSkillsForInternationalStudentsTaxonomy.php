<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

use Fau\DegreeProgram\Common\Application\Filter\GermanLanguageSkillsForInternationalStudentsFilter;

/**
 * Deutschkenntnisse für ausländische Studierende
 */
final class GermanLanguageSkillsForInternationalStudentsTaxonomy extends Taxonomy
{
    public const KEY = 'german_for_int_students';
    public const REST_BASE = GermanLanguageSkillsForInternationalStudentsFilter::KEY;

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
            'German language skills for international students',
            'backoffice: taxonomy plural name',
            'fau-degree-program-common'
        );
    }

    protected function singularName(): string
    {
        return _x(
            'German language skills for international students',
            'backoffice: taxonomy singular name',
            'fau-degree-program-common'
        );
    }

    protected function isHierarchical(): bool
    {
        return true;
    }
}
