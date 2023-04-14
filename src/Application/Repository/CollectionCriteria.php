<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Webmozart\Assert\Assert;

/**
 * @psalm-type SupportedFilterTypes = 'degree';
 * @psalm-type SupportedArgs = array{
 *    page: int,
 *    per_page: int,
 *    include?: array<int>,
 *    search?: string,
 * }
 */
final class CollectionCriteria
{
    /**
     * @param SupportedArgs $args
     * @param array<SupportedFilterTypes, array<int>> $filters
     */
    private function __construct(
        private array $args,
        private array $filters
    ) {
    }

    public static function new(): self
    {
        return new self([
            'page' => 1,
            'per_page' => 10,
        ], []);
    }

    public function toNextPage(): self
    {
        $instance = clone $this;
        $instance->args['page']++;

        return $instance;
    }

    public function withPage(int $page): self
    {
        Assert::positiveInteger($page);

        $instance = clone $this;
        $instance->args['page'] = $page;

        return $instance;
    }

    public function page(): int
    {
        return $this->args['page'];
    }

    public function withPerPage(int $perPage): self
    {
        Assert::greaterThanEq($perPage, -1);
        Assert::notEq($perPage, 0);

        $instance = clone $this;
        $instance->args['per_page'] = $perPage;

        return $instance;
    }

    public function perPage(): int
    {
        return $this->args['per_page'];
    }

    public function withoutPagination(): self
    {
        $instance = clone $this;
        $instance->args['per_page'] = -1;

        return $instance;
    }

    /**
     * @param array<int> $include
     */
    public function withInclude(array $include): self
    {
        Assert::allPositiveInteger($include);

        $instance = clone $this;
        $instance->args['include'] = $include;

        return $instance;
    }

    /**
     * @psalm-param SupportedFilterTypes $filterType
     */
    public function withFilter(string $filterType, int ...$values): self
    {
        $instance = clone $this;
        $instance->filters[$filterType] = $values;

        return $instance;
    }

    public function withDegree(int ...$values): self
    {
        return $this->withFilter('degree', ...$values);
    }

    public function withSearchKeyword(string $keyword): self
    {
        $instance = clone $this;
        $instance->args['search'] = $keyword;

        return $instance;
    }

    /**
     * @psalm-return SupportedArgs
     */
    public function args(): array
    {
        return $this->args;
    }

    /**
     * @psalm-return array<SupportedFilterTypes, array<int>>
     */
    public function filters(): array
    {
        return $this->filters;
    }
}
