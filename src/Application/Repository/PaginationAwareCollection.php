<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Traversable;

/**
 * @template T of \Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw
 *              | \Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated
 * @template-extends Traversable<T>
 */
interface PaginationAwareCollection extends Traversable
{
    public function currentPage(): int;

    public function nextPage(): ?int;

    public function previousPage(): ?int;

    public function maxPages(): int;

    public function totalItems(): int;
}
