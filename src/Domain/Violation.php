<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use JsonSerializable;

/**
 * @psalm-type ViolationType = array{
 *     path: string,
 *     errorMessage: string,
 *     errorCode: string
 * }
 */
final class Violation implements JsonSerializable
{
    private function __construct(
        private string $path,
        private string $errorMessage,
        private string $errorCode,
    ) {
    }

    public static function new(
        string $path,
        string $errorMessage,
        string $errorCode,
    ): self {

        return new self($path, $errorMessage, $errorCode);
    }

    /**
     * @return ViolationType
     */
    public function asArray(): array
    {
        return [
            'path' => $this->path(),
            'errorMessage' => $this->errorMessage(),
            'errorCode' => $this->errorCode(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }

    public function path(): string
    {
        return $this->path;
    }

    public function readablePath(): string
    {
        $result = str_replace('.id', '', $this->path);
        $result = ucwords($result, '.');
        return str_replace(['_', '.'], [' ', ' - '], $result);
    }

    public function errorMessage(): string
    {
        return $this->errorMessage;
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }
}
