<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class WpQueryArgs
{
    public function __construct(private array $args)
    {
    }

    public function args(): array
    {
        return $this->args;
    }

    public function arg(string $key): mixed
    {
        return $this->args[$key] ?? null;
    }

    public function withArg(string $key, mixed $value): self
    {
        $instance = clone $this;

        $instance->args[$key] = $value;

        return $instance;
    }

    public function withOrderBy(array $orderBy): self
    {
        return $this->withArg('orderby', $orderBy);
    }

    public function withTaxQueryItem(array $item, string $relation = 'AND'): self
    {
        $instance = clone $this;

        $instance->args['tax_query'] = (array) ($instance->args['tax_query']
            ?? ['relation' => $relation]);

        $instance->args['tax_query'][] = $item;

        return $instance;
    }

    public function withMetaQueryItem(array $item): self
    {
        $instance = clone $this;

        $instance->args['meta_query'] = (array) ($instance->args['meta_query']
            ?? ['relation' => 'AND']);

        $instance->args['meta_query'][] = $item;

        return $instance;
    }

    public function withMetaKey(string $metaKey): self
    {
        $instance = clone $this;

        $instance->args['meta_key'] = $metaKey;

        return $instance;
    }
}
