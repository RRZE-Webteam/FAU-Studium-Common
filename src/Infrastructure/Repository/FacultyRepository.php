<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\FacultyTaxonomy;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;

final class FacultyRepository
{
    public function findFacultySlugs(DegreeProgramViewRaw $raw): ArrayOfStrings
    {
        $terms = get_the_terms(
            $raw->id()->asInt(),
            FacultyTaxonomy::KEY
        );

        if (!is_array($terms)) {
            return ArrayOfStrings::new();
        }

        /** @var array<string> $slugs */
        $slugs = wp_list_pluck($terms, 'slug');

        return ArrayOfStrings::new(...$slugs);
    }
}
