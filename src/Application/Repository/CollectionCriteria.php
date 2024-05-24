<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Fau\DegreeProgram\Common\Application\Filter\Filter;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Webmozart\Assert\Assert;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type OrderBy = array<string, 'asc' | 'desc'>
 * @psalm-type SupportedArgs = array{
 *    page: int,
 *    per_page: int,
 *    include?: array<int>,
 *    search?: string,
 *    order_by: OrderBy,
 *    his_codes?: array<string>
 * }
 */
final class CollectionCriteria
{
    public const SORTABLE_PROPERTIES = [
        DegreeProgram::TITLE,
        DegreeProgram::DEGREE,
        DegreeProgram::START,
        DegreeProgram::LOCATION,
        DegreeProgram::ADMISSION_REQUIREMENTS,
        DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS,
    ];

    /**
     * @var Filter[]
     */
    private array $filters = [];

    /**
     * @var array<string>
     */
    private array $hisCodes = [];

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
            'order_by' => [],
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
        $this->filters = array_merge(
            $this->filters(),
            array_filter(
                $filters,
                static fn (Filter $filter) => !empty($filter->value()),
            )
        );

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
     * @psalm-param LanguageCodes $languageCode
     */
    public function withLanguage(string $languageCode): self
    {
        $instance = clone $this;

        $instance->languageCode = $languageCode;

        return $instance;
    }

    /**
     * @psalm-param OrderBy $orderBy
     */
    public function withOrderBy(array $orderBy): self
    {
        $instance = clone $this;
        $sanitizedValue = [];
        foreach ($orderBy as $property => $order) {
            if (!in_array($property, self::SORTABLE_PROPERTIES, true)) {
                continue;
            }

            $sanitizedValue[$property] = $order === 'asc' ? 'asc' : 'desc';
        }

        $instance->args['order_by'] = $sanitizedValue;
        return $instance;
    }

    /**
     * @param array<string> $hisCodes
     * @return self
     */
    public function withHisCodes(array $hisCodes): self
    {
        $instance = clone $this;
        $instance->hisCodes = $hisCodes;
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

    /**
     * @return array<string>
     */
    public function hisCodes(): array
    {
        return $this->hisCodes;
    }
}
