<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Webmozart\Assert\Assert;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type SupportedArgs = array{
 *    page: int,
 *    per_page: int,
 *    include?: array<int>,
 *    search?: string,
 *    orderby?: string,
 *    order?: 'asc' | 'desc',
 * }
 */
final class CollectionCriteria
{
    public const SORTABLE_PROPERTIES = [
        DegreeProgram::ID,
        DegreeProgram::TITLE,
        DegreeProgram::DEGREE,
        DegreeProgram::START,
        DegreeProgram::LOCATION,
        DegreeProgram::ADMISSION_REQUIREMENTS,
    ];
    public const DEFAULT_ORDERBY = ['date', 'desc'];

    /**
     * @var Filter[]
     */
    private array $filters = [];

    /**
     * @var LanguageCodes|null
     */
    private ?string $languageCode = null;

    /**
     * @param SupportedArgs $args
     */
    private function __construct(
        private array $args,
    ) {
    }

    public static function new(): self
    {
        return new self([
            'page' => 1,
            'per_page' => 10,
        ]);
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

    /**
     * @psalm-return LanguageCodes|null
     */
    public function languageCode(): ?string
    {
        return $this->languageCode;
    }

    public function addFilter(Filter ...$filters): self
    {
        $this->filters = array_merge($this->filters(), $filters);

        return $this;
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
     * @param LanguageCodes $languageCode
     * @return self
     */
    public function withLanguage(string $languageCode): self
    {
        $instance = clone $this;

        $this->languageCode = $languageCode;

        return $instance;
    }

    /**
     * @param 'asc' | 'desc' $order
     */
    public function withOrderby(string $orderBy, string $order = 'desc'): self
    {
        $instance = clone $this;

        if (!in_array($orderBy, self::SORTABLE_PROPERTIES, true)) {
            $instance->args['orderby'] = self::DEFAULT_ORDERBY[0];
            $instance->args['order'] = self::DEFAULT_ORDERBY[1];
        }

        $instance->args['orderby'] = $orderBy;
        $instance->args['order'] = $order === 'asc' ? 'asc' : 'desc';

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
     * @psalm-return Filter[]
     */
    public function filters(): array
    {
        return $this->filters;
    }
}
