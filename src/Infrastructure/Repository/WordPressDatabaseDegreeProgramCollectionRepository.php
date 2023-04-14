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
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use WP_Query;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class WordPressDatabaseDegreeProgramCollectionRepository implements DegreeProgramCollectionRepository
{
    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private TaxonomiesList $taxonomiesList,
    ) {
    }

    public function findRawCollection(CollectionCriteria $criteria): PaginationAwareCollection
    {
        $query = new WP_Query();
        /** @var array<int> $ids */
        $ids = $query->query(
            $this->prepareWpQueryArgs($criteria)
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
            $this->prepareWpQueryArgs($criteria, $languageCode)
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
     *
     * @psalm-param ?LanguageCodes $languageCode
     */
    private function prepareWpQueryArgs(CollectionCriteria $criteria, ?string $languageCode = null): array
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
            if ($key === 'search') {
                continue;
            }

            $normalizedArgs[$aliases[$key] ?? $key] = $value;
        }

        $normalizedArgs['tax_query'] = $this->buildTaxQuery($criteria);
        $normalizedArgs['meta_query'] = ['relation' => 'AND'];
        $searchKeyword = $criteria->args()['search'] ?? null;

        if ($searchKeyword) {
            $normalizedArgs['meta_query'][] = $this->buildSearchMetaQuery(
                $searchKeyword,
                $languageCode,
            );
        }

        return array_merge($normalizedArgs, $requiredArgs);
    }

    private function buildTaxQuery(CollectionCriteria $criteria): array
    {
        $taxQuery = [];
        foreach ($criteria->filters() as $filterType => $values) {
            $taxonomyKey = $this->taxonomiesList->convertRestBaseToSlug($filterType);
            if (!$taxonomyKey) {
                continue;
            }

            $taxQuery[] = [
                'taxonomy' => $taxonomyKey,
                'terms' => $values,
            ];
        }

        return $taxQuery;
    }

    /**
     * @psalm-param ?LanguageCodes $languageCode
     */
    private function buildSearchMetaQuery(string $keyword, ?string $languageCode = null): array
    {
        // Finding raw (un-translated) collection? include searchable content in both languages
        if ($languageCode === null) {
            return [
                'relation' => 'OR',
                [
                    'key' => 'fau_degree_program_searchable_content_' . MultilingualString::EN,
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ],
                [
                    'key' => 'fau_degree_program_searchable_content_' . MultilingualString::DE,
                    'value' => $keyword,
                    'compare' => 'LIKE',
                ],
            ];
        }

        return [
            'key' => 'fau_degree_program_searchable_content_' . $languageCode,
            'value' => $keyword,
            'compare' => 'LIKE',
        ];
    }
}
