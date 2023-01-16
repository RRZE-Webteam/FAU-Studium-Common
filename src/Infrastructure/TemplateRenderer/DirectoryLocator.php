<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer;

use UnexpectedValueException;

final class DirectoryLocator implements Locator
{
    /** @var array<string> */
    private array $directories = [];

    /** @var array<string, string|null> */
    private array $cachedPaths = [];

    private function __construct(string ...$directories)
    {
        $this->directories = $directories;
    }

    public static function new(string ...$directories): self
    {
        return new self(...$directories);
    }

    public function locate(string $templateName): string
    {
        $resolvedPath = $this->resolvePath($templateName);

        if ($resolvedPath !== null) {
            return $resolvedPath;
        }

        throw new UnexpectedValueException(
            sprintf(
                'Could not find template "%s".',
                $templateName,
            )
        );
    }

    private function resolvePath(string $templateName): ?string
    {
        $normalizedTemplateName = wp_normalize_path($templateName);

        if (array_key_exists($normalizedTemplateName, $this->cachedPaths)) {
            return $this->cachedPaths[$normalizedTemplateName];
        }

        $templateFile = $this->maybeAddExtension($normalizedTemplateName);

        if (count($this->directories) === 0) {
            // If no directory is registered, we expect `locate()` to receive an absolute path
            $this->directories = ['/'];
        }

        foreach ($this->directories as $directory) {
            $path = wp_normalize_path($directory . '/' . $templateFile);

            if (self::isValidFile($path)) {
                $this->cachedPaths[$normalizedTemplateName] = $path;
                return $this->cachedPaths[$normalizedTemplateName];
            }
        }

        $this->cachedPaths[$normalizedTemplateName] = null;
        return null;
    }

    private function maybeAddExtension(string $templateName): string
    {
        return str_ends_with($templateName, '.php')
            ? $templateName
            : $templateName . '.php';
    }

    private static function isValidFile(string $path): bool
    {
        if (!$path) {
            return false;
        }

        /** @var array<string, bool> $files */
        static $files = [];
        if (!isset($files[$path])) {
            $files[$path] = is_file($path) && is_readable($path);
        }

        return $files[$path];
    }
}
