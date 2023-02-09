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
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use WP_Query;

final class WordPressDatabaseDegreeProgramCollectionRepository implements DegreeProgramCollectionRepository
{
    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
    ) {
    }

    public function findRawCollection(CollectionCriteria $criteria): PaginationAwareCollection
    {
        $query = new WP_Query();
        /** @var array<int> $ids */
        $ids = $query->query(
            self::prepareWpQueryArgs($criteria)
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
        /** @var array<int> $ids */
        $ids = $query->query(
            self::prepareWpQueryArgs($criteria)
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

    /**
     * The permanent degree program cache is used
     * instead of WordPress internal cache.
     */
    private static function prepareWpQueryArgs(CollectionCriteria $criteria): array
    {
        /** @var array<string, string> $aliases */
        static $aliases = [
            'page' => 'paged',
            'per_page' => 'posts_per_page',
            'include' => 'post__in',
        ];

        /** @var array<string, mixed> $requiredArgs */
        static $requiredArgs = [
            'post_type' => DegreeProgramPostType::KEY,
            'post_status' => 'publish',
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ];

        $normalizedArgs = [];
        foreach ($criteria->args() as $key => $value) {
            $normalizedArgs[$aliases[$key] ?? $key] = $value;
        }

        return array_merge($normalizedArgs, $requiredArgs);
    }
}
