<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class FilterFactory
{
    public const SUPPORTED_FILTERS = [
        AdmissionRequirementTypeFilter::KEY => AdmissionRequirementTypeFilter::class,
        AreaOfStudyFilter::KEY => AreaOfStudyFilter::class,
        AttributeFilter::KEY => AttributeFilter::class,
        DegreeFilter::KEY => DegreeFilter::class,
        FacultyFilter::KEY => FacultyFilter::class,
        GermanLanguageSkillsForInternationalStudentsFilter::KEY => GermanLanguageSkillsForInternationalStudentsFilter::class,
        SearchKeywordFilter::KEY => SearchKeywordFilter::class,
        SemesterFilter::KEY => SemesterFilter::class,
        StudyLocationFilter::KEY => StudyLocationFilter::class,
        SubjectGroupFilter::KEY => SubjectGroupFilter::class,
        TeachingLanguageFilter::KEY => TeachingLanguageFilter::class,
    ];

    public function create(string $filterName, mixed $value): ?Filter
    {
        $className = self::SUPPORTED_FILTERS[$filterName] ?? null;

        if (!$className) {
            return null;
        }

        if ($value === null) {
            return $className::empty();
        }

        /**
         * @var Filter $filter
         * phpcs:disable NeutronStandard.Functions.DisallowCallUserFunc.CallUserFunc
         */
        $filter = call_user_func([$className, 'fromInput'], $value);
        return $filter;
    }

    /**
     * @param array<string, mixed> $list
     * @return array<Filter>
     */
    public function bulk(array $list): array
    {
        $result = [];
        foreach ($list as $name => $value) {
            $result[] = $this->create($name, $value);
        }

        return array_filter($result);
    }
}
