<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Webmozart\Assert\Assert;

/**
 * @psalm-type SupportedArgs = array{
 *    page: int,
 *    per_page: int,
 *    include?: array<int>,
 * }
 */
final class CollectionCriteria
{
    /**
     * @param SupportedArgs $args
     */
    private function __construct(private array $args)
    {
        Assert::positiveInteger($this->args['page']);
        Assert::greaterThanEq($this->args['per_page'], -1);
        Assert::notEq($this->args['per_page'], 0);
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
        $this->args['page']++;

        return new self($this->args);
    }

    public function withPage(int $page): self
    {
        $this->args['page'] = $page;

        return new self($this->args);
    }

    public function page(): int
    {
        return $this->args['page'];
    }

    public function withPerPage(int $perPage): self
    {
        $this->args['per_page'] = $perPage;

        return new self($this->args);
    }

    /**
     * @param array<int> $include
     */
    public function withInclude(array $include): self
    {
        Assert::allPositiveInteger($include);

        $this->args['include'] = $include;

        return new self($this->args);
    }

    /**
     * @psalm-return SupportedArgs
     */
    public function args(): array
    {
        return $this->args;
    }
}
