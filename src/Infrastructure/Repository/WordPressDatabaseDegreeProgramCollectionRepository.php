<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use WP_Query;

final class WordPressDatabaseDegreeProgramCollectionRepository implements DegreeProgramCollectionRepository
{
    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private WpQueryArgsBuilder $queryArgsBuilder,
        private WpQuerySplitter $querySplitter
    ) {
    }

    public function findRawCollection(CollectionCriteria $criteria): PaginationAwareCollection
    {
        $query = new WP_Query();
        $criteria = $this->querySplitter->maybeSplitQuery($criteria);
        /** @var array<int> $ids */
        $ids = $query->query(
            $this->queryArgsBuilder
                ->build($criteria)
                ->args()
        );

        $items = [];
        foreach ($ids as $id) {
            $view = $this->degreeProgramViewRepository->findRaw(
                DegreeProgramId::fromInt($id),
            );

            if ($view instanceof DegreeProgramViewRaw) {
                $items[] = $view;
            }
        }

        return new WpQueryPaginationAwareCollection($query, ...$items);
    }

    public function findTranslatedCollection(CollectionCriteria $criteria, string $languageCode): PaginationAwareCollection
    {
        $query = new WP_Query();
        $criteria = $this->querySplitter->maybeSplitQuery(
            $criteria->withLanguage($languageCode)
        );

        /** @var array<int> $ids */
        $ids = $query->query(
            $this->queryArgsBuilder
                ->build($criteria->withLanguage($languageCode))
                ->args()
        );

        $items = [];
        foreach ($ids as $id) {
            $view = $this->degreeProgramViewRepository->findTranslated(
                DegreeProgramId::fromInt($id),
                $languageCode
            );

            if ($view instanceof DegreeProgramViewTranslated) {
                $items[] = $view;
            }
        }

        return new WpQueryPaginationAwareCollection($query, ...$items);
    }
}
