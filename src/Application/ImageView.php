<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\Image;
use Webmozart\Assert\Assert;

/**
 * @psalm-type ImageViewType = array{id: int, url: string, rendered: string}
 */
final class ImageView
{
    public const RENDERED = 'rendered';

    private function __construct(
        private int $id,
        private string $url,
        private string $rendered,
    ) {

        Assert::greaterThanEq($id, 0);
    }

    public static function empty(): self
    {
        return new self(0, '', '');
    }

    public static function new(int $id, string $url, string $rendered): self
    {
        return new self(
            $id,
            $url,
            $rendered
        );
    }

    /**
     * @psalm-param ImageViewType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data[Image::ID],
            $data[Image::URL],
            $data[self::RENDERED],
        );
    }

    /**
     * @return ImageViewType
     */
    public function asArray(): array
    {
        return [
            Image::ID => $this->id,
            Image::URL => $this->url,
            self::RENDERED => $this->rendered,
        ];
    }

    public function id(): int
    {
        return $this->id;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function rendered(): string
    {
        return $this->rendered;
    }
}
