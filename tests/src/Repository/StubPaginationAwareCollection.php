<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Repository;

use ArrayObject;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;

final class StubPaginationAwareCollection extends ArrayObject implements PaginationAwareCollection
{
    public function __construct(
        private int $currentPage = 1,
        private ?int $nextPage = null,
        private ?int $previousPage = null,
        private int $maxPages = 1,
        private int $totalItems = 0,
        DegreeProgramViewRaw | DegreeProgramViewTranslated ...$items
    ) {

        parent::__construct($items);
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function nextPage(): ?int
    {
        return $this->nextPage;
    }

    public function previousPage(): ?int
    {
        return $this->previousPage;
    }

    public function maxPages(): int
    {
        return $this->maxPages;
    }

    public function totalItems(): int
    {
        return $this->totalItems;
    }
}
