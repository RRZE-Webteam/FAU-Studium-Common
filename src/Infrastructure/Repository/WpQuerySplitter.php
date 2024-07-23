<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use WP_Query;

final class WpQuerySplitter
{
    public function __construct(
        private WpQueryArgsBuilder $queryArgsBuilder
    ) {
    }

    /**
     * @param CollectionCriteria $criteria
     * @return CollectionCriteria
     */
    public function maybeSplitQuery(CollectionCriteria $criteria): CollectionCriteria
    {
        $hisCodes = $criteria->hisCodes();

        if (count($hisCodes) <= 1) {
            return $criteria;
        }

        $ids = [];

        foreach ($hisCodes as $hisCode) {
            $criteria = $criteria->withHisCodes([$hisCode]);
            $query = new WP_Query();
            /** @var array<int> $ids */
            $ids = array_merge(
                $query->query(
                    $this->queryArgsBuilder
                        ->build($criteria)
                        ->args()
                ),
                $ids
            );
        }

        return $criteria
            ->withHisCodes([])
            ->withInclude($ids);
    }
}
