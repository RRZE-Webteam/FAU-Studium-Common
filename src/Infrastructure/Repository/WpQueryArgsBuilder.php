<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\Filter\AreaOfStudyFilter;
use Fau\DegreeProgram\Common\Application\Filter\AttributeFilter;
use Fau\DegreeProgram\Common\Application\Filter\DegreeFilter;
use Fau\DegreeProgram\Common\Application\Filter\FacultyFilter;
use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Application\Filter\SearchKeywordFilter;
use Fau\DegreeProgram\Common\Application\Filter\SemesterFilter;
use Fau\DegreeProgram\Common\Application\Filter\StudyLocationFilter;
use Fau\DegreeProgram\Common\Application\Filter\SubjectGroupFilter;
use Fau\DegreeProgram\Common\Application\Filter\TeachingLanguageFilter;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;

final class WpQueryArgsBuilder
{
    private const TAXONOMY_BASED_FILTERS = [
        AreaOfStudyFilter::class,
        AttributeFilter::class,
        DegreeFilter::class,
        FacultyFilter::class,
        SemesterFilter::class,
        StudyLocationFilter::class,
        SubjectGroupFilter::class,
        TeachingLanguageFilter::class,
    ];

    private const ALIASES = [
        'page' => 'paged',
        'per_page' => 'posts_per_page',
        'include' => 'post__in',
    ];

    private const DEFAULTS = [
        'post_type' => DegreeProgramPostType::KEY,
        'post_status' => 'publish',
        'fields' => 'ids',
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ];

    public function __construct(private TaxonomiesList $taxonomiesList)
    {
    }

    public function build(CollectionCriteria $criteria): WpQueryArgs
    {
        $queryArgs = (new WpQueryArgs(self::DEFAULTS));

        foreach ($criteria->args() as $key => $arg) {
            $queryArgs = $queryArgs->withArg(self::ALIASES[$key] ?? $key, $arg);
        }

        // Apply ordering
        $orderBy = $criteria->args()['orderby'] ?? '';
        $order = $criteria->args()['order'] ?? '';

        if ($orderBy) {
            $queryArgs = $this->applyOrderBy($orderBy, $queryArgs, $criteria->languageCode());
            $queryArgs = $queryArgs->withArg('order', $order);
        }

        // Apply filters
        foreach ($criteria->filters() as $filter) {
            $queryArgs = $this->applyFilter($filter, $queryArgs, $criteria->languageCode());
        }

        return $queryArgs;
    }

    private function applyOrderBy(string $orderBy, WpQueryArgs $queryArgs, ?string $languageCode = null): WpQueryArgs
    {
        $languageCode = $languageCode ?? MultilingualString::DE;

        return match ($orderBy) {
            DegreeProgram::TITLE => $languageCode === MultilingualString::DE
                ? $queryArgs->orderbyTitle()
                : $queryArgs->withMetaKey('title_' . $languageCode)
                    ->orderbyMeta(),

            DegreeProgram::DEGREE,
            DegreeProgram::START,
            DegreeProgram::LOCATION,
            DegreeProgram::ADMISSION_REQUIREMENTS =>
                $queryArgs->withMetaKey(
                    $orderBy . '_' . $languageCode
                )
                    ->orderbyMeta(),
            default => $queryArgs,
        };
    }

    private function applyFilter(Filter $filter, WpQueryArgs $queryArgs, ?string $languageCode = null): WpQueryArgs
    {
        $isTaxonomyFilter = in_array(get_class($filter), self::TAXONOMY_BASED_FILTERS, true);

        return match (true) {
            $isTaxonomyFilter => $this->applyTaxonomyFilter($filter, $queryArgs),
            $filter instanceof SearchKeywordFilter => $this->applySearchFilter(
                $filter,
                $queryArgs,
                $languageCode
            ),
            default => $queryArgs,
        };
    }

    private function applyTaxonomyFilter(Filter $filter, WpQueryArgs $queryArgs): WpQueryArgs
    {
        return $queryArgs->withTaxQueryItem(
            [
                'taxonomy' => $this->taxonomiesList->convertRestBaseToSlug($filter->id()),
                'terms' => (array) $filter->value(),
            ]
        );
    }

    private function applySearchFilter(SearchKeywordFilter $filter, WpQueryArgs $queryArgs, ?string $languageCode = null): WpQueryArgs
    {
        if (!$languageCode) {
            return $queryArgs->withMetaQueryItem(
                [
                    'relation' => 'OR',
                    [
                        'key' => 'fau_degree_program_searchable_content_' . MultilingualString::EN,
                        'value' => $filter->value(),
                        'compare' => 'LIKE',
                    ],
                    [
                        'key' => 'fau_degree_program_searchable_content_' . MultilingualString::DE,
                        'value' => $filter->value(),
                        'compare' => 'LIKE',
                    ],
                ]
            );
        }

        return $queryArgs->withMetaQueryItem(
            [
                'key' => 'fau_degree_program_searchable_content_' . $languageCode,
                'value' => $filter->value(),
                'compare' => 'LIKE',
            ]
        );
    }
}
