<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use ArrayObject;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use WP_Query;

/**
 * @template T of DegreeProgramViewRaw | DegreeProgramViewTranslated
 * @template-extends ArrayObject<array-key, T>
 * @template-implements PaginationAwareCollection<T>
 */
final class WpQueryPaginationAwareCollection extends ArrayObject implements PaginationAwareCollection
{
    /**
     * @param WP_Query $query WP_Query::get_posts() method must be run before
     *                        to make pagination works properly
     * @param T ...$items
     */
    public function __construct(
        private WP_Query $query,
        DegreeProgramViewRaw | DegreeProgramViewTranslated ...$items
    ) {

        parent::__construct($items);
    }

    public function currentPage(): int
    {
        return (int) $this->query->get('paged', 1);
    }

    public function nextPage(): ?int
    {
        if ($this->currentPage() >= $this->maxPages()) {
            return null;
        }

        return $this->currentPage() + 1;
    }

    public function previousPage(): ?int
    {
        if ($this->currentPage() <= 1) {
            return null;
        }

        return min(($this->currentPage() - 1), $this->maxPages());
    }

    public function maxPages(): int
    {
        return (int) ceil($this->totalItems() / (int) $this->query->get('posts_per_page'));
    }

    public function totalItems(): int
    {
        /** @var int|null $total */
        static $total;

        if (isset($total)) {
            return $total;
        }

        $total = $this->query->found_posts;

        if ($total < 1 && $this->currentPage() > 1) {
            // Out-of-bounds, run the query again without LIMIT for total count.
            $args = $this->query->query_vars;
            unset($args['paged']);
            $countQuery = new WP_Query();
            $countQuery->query($args);
            $total = $countQuery->found_posts;
        }

        return $total;
    }
}
