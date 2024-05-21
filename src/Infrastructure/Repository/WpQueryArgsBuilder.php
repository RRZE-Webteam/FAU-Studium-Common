<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\Filter\AdmissionRequirementTypeFilter;
use Fau\DegreeProgram\Common\Application\Filter\AreaOfStudyFilter;
use Fau\DegreeProgram\Common\Application\Filter\AttributeFilter;
use Fau\DegreeProgram\Common\Application\Filter\DegreeFilter;
use Fau\DegreeProgram\Common\Application\Filter\FacultyFilter;
use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Application\Filter\GermanLanguageSkillsForInternationalStudentsFilter;
use Fau\DegreeProgram\Common\Application\Filter\SearchKeywordFilter;
use Fau\DegreeProgram\Common\Application\Filter\SemesterFilter;
use Fau\DegreeProgram\Common\Application\Filter\StudyLocationFilter;
use Fau\DegreeProgram\Common\Application\Filter\SubjectGroupFilter;
use Fau\DegreeProgram\Common\Application\Filter\TeachingLanguageFilter;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Domain\CampoKeys;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\BachelorOrTeachingDegreeAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\MasterDegreeAdmissionRequirementTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TaxonomiesList;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy;
use WP_Term;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-import-type OrderBy from CollectionCriteria
 */
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
        GermanLanguageSkillsForInternationalStudentsFilter::class,
    ];

    private const ALIASES = [
        'page' => 'paged',
        'per_page' => 'posts_per_page',
        'include' => 'post__in',
        'order_by' => 'orderby',
    ];

    private const DEFAULTS = [
        'post_type' => DegreeProgramPostType::KEY,
        'post_status' => 'publish',
        'fields' => 'ids',
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ];

    private const DEFAULT_ORDER_BY = [
        DegreeProgram::TITLE => 'asc',
        'date' => 'desc',
    ];

    public function __construct(
        private TaxonomiesList $taxonomiesList,
        private CampoKeysRepository $campoKeysRepository,
    ) {
    }

    public function build(CollectionCriteria $criteria): WpQueryArgs
    {
        $queryArgs = (new WpQueryArgs(self::DEFAULTS));

        foreach ($criteria->args() as $key => $arg) {
            $queryArgs = $queryArgs->withArg(self::ALIASES[$key] ?? $key, $arg);
        }

        $queryArgs = $this->applyOrderBy($criteria, $queryArgs);
        $queryArgs = $this->translateOrderBy($criteria, $queryArgs);

        // Apply filters
        foreach ($criteria->filters() as $filter) {
            $queryArgs = $this->applyFilter($filter, $queryArgs, $criteria->languageCode());
        }

        foreach ($criteria->hisCodes() as $hisCode) {
            $queryArgs = $this->applyHisCode($hisCode, $queryArgs);
        }

        return $queryArgs;
    }

    public function applyHisCode(string $hisCode, WpQueryArgs $queryArgs): WpQueryArgs
    {
        $taxQueryItem = [
            'relation' => 'AND',
        ];

        $taxonomyToTermMapping = $this->campoKeysRepository->taxonomyToTermsMapFromCampoKeys(
            CampoKeys::fromHisCode($hisCode)
        );

        if (count($taxonomyToTermMapping) === 0) {
            return $queryArgs;
        }

        foreach ($taxonomyToTermMapping as $taxonomy => $termId) {
            $taxQueryItem[] = [
                'taxonomy' => $taxonomy,
                'terms' => [
                    $termId,
                ],
            ];
        }

        return $queryArgs->withTaxQueryItem($taxQueryItem);
    }

    private function applyOrderBy(
        CollectionCriteria $criteria,
        WpQueryArgs $queryArgs
    ): WpQueryArgs {

        $orderBy = $criteria->args()['order_by'] ?? null;
        if ($orderBy) {
            // Order is defined explicitly
            return $queryArgs;
        }

        $currentTerm = $this->currentTerm($criteria);
        $hasSearch = !empty($criteria->args()['search']);
        $hasFilters = count($criteria->filters()) > 0;
        $hasTerm = $currentTerm instanceof WP_Term;
        if ($hasSearch || ($hasFilters && !$hasTerm)) {
            // We can not detect current term
            return $queryArgs->withOrderBy(self::DEFAULT_ORDER_BY);
        }

        // No filters or single filter with detected term
        $stickyKey = StickyDegreeProgramRepository::stickyKey($currentTerm);
        return $queryArgs->withOrderBy(array_merge(
            [$stickyKey => 'desc'],
            self::DEFAULT_ORDER_BY,
        ));
    }

    private function translateOrderBy(
        CollectionCriteria $criteria,
        WpQueryArgs $queryArgs
    ): WpQueryArgs {

        $languageCode = $criteria->languageCode() ?? MultilingualString::DE;
        /** @var OrderBy | null $orderBy */
        $orderBy = $queryArgs->args()['orderby'] ?? null;
        if (!$orderBy) {
            return $queryArgs;
        }

        $translatedOrderBy = [];
        foreach ($orderBy as $property => $order) {
            $key = $this->translateOrderByProperty($property, $languageCode);
            $translatedOrderBy[$key] = $order;
        }

        return $queryArgs->withOrderBy($translatedOrderBy);
    }

    /**
     * @psalm-param string $property
     * @psalm-param LanguageCodes $languageCode
     */
    private function translateOrderByProperty(
        string $property,
        string $languageCode,
    ): string {

        if (!in_array($property, CollectionCriteria::SORTABLE_PROPERTIES, true)) {
            return $property;
        }

        if (
            $property === DegreeProgram::TITLE
            && $languageCode === MultilingualString::DE
        ) {
            return $property;
        }

        return implode('_', [$property, $languageCode]);
    }

    private function applyFilter(Filter $filter, WpQueryArgs $queryArgs, ?string $languageCode = null): WpQueryArgs
    {
        return match (true) {
            $this->isTaxonomyFilter($filter) => $this->applyTaxonomyFilter($filter, $queryArgs),
            $filter instanceof SearchKeywordFilter => $this->applySearchFilter(
                $filter,
                $queryArgs,
                $languageCode
            ),
            $filter instanceof AdmissionRequirementTypeFilter => $this->applyAdmissionRequirementFilter(
                $filter,
                $queryArgs,
                $languageCode
            ),
            default => $queryArgs,
        };
    }

    private function isTaxonomyFilter(Filter $filter): bool
    {
        return in_array(get_class($filter), self::TAXONOMY_BASED_FILTERS, true);
    }

    private function applyAdmissionRequirementFilter(
        AdmissionRequirementTypeFilter $filter,
        WpQueryArgs $queryArgs,
        ?string $languageCode = null
    ): WpQueryArgs {

        $languageCode = $languageCode ?: MultilingualString::DE;

        return $queryArgs->withTaxQueryItem(
            [
                'relation' => 'OR',
                [
                    'taxonomy' => BachelorOrTeachingDegreeAdmissionRequirementTaxonomy::KEY,
                    'terms' => $filter->value(),
                    'field' => 'slug',
                    'compare' => 'IN',
                    'include_children' => true,
                ],
                [
                    'taxonomy' => TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy::KEY,
                    'terms' => $filter->value(),
                    'field' => 'slug',
                    'compare' => 'IN',
                    'include_children' => true,
                ],
                [
                    'taxonomy' => MasterDegreeAdmissionRequirementTaxonomy::KEY,
                    'terms' => $filter->value(),
                    'field' => 'slug',
                    'compare' => 'IN',
                    'include_children' => true,
                ],
            ]
        );
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

    private function currentTerm(CollectionCriteria $criteria): ?WP_Term
    {
        if (count($criteria->filters()) !== 1) {
            return null;
        }

        $filter = $criteria->filters()[0];
        if (!$this->isTaxonomyFilter($filter)) {
            return null;
        }

        $values = $filter->value();
        if (!is_array($values) || count($values) !== 1) {
            // Multiple terms are ignored
            return null;
        }

        $term = (int) $values[0];
        if (!$term) {
            return null;
        }
        $taxonomy = $this->taxonomiesList->convertRestBaseToSlug($filter->id());
        if (!$taxonomy) {
            return null;
        }
        $term = get_term($term, $taxonomy);

        return $term instanceof WP_Term ? $term : null;
    }
}
