<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
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

    public function orderbyTitle(): self
    {
        return $this->withArg('orderby', 'title');
    }

    public function orderbyMeta(): self
    {
        return $this->withArg('orderby', 'meta_value');
    }

    public function orderbyMetaNumeric(): self
    {
        return $this->withArg('orderby', 'meta_value_num');
    }

    public function withArg(string $key, mixed $value): self
    {
        $instance = clone $this;

        $instance->args[$key] = $value;

        return $instance;
    }

    public function withOrderby(string $value): self
    {
        return $this->withArg('orderby', $value);
    }

    public function withTaxQueryItem(array $item): self
    {
        $instance = clone $this;

        $instance->args['tax_query'] = (array) ($instance->args['tax_query']
            ?? ['relation' => 'AND']);

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
