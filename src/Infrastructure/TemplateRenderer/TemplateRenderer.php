<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer;

use RuntimeException;
use Throwable;

final class TemplateRenderer implements Renderer
{
    private Locator $locator;

    private function __construct(
        Locator $locator,
    ) {

        $this->locator = $locator;
    }

    public static function new(
        Locator $locator,
    ): self {

        return new self($locator);
    }

    public function render(string $templateName, array $data = []): string
    {
        $level = ob_get_level();
        /** @psalm-suppress RedundantCondition */
        $isDebug = defined('WP_DEBUG') && WP_DEBUG;

        try {
            $fullPath = $this->locator->locate($templateName);

            ob_start();

            // phpcs:disable Inpsyde.CodeQuality.VariablesName.SnakeCaseVar
            /** @psalm-suppress UnusedClosureParam */
            (static function (string $__fullPath__, array $data, Renderer $renderer): void {
                /** @psalm-suppress UnresolvableInclude */
                include $__fullPath__;
            })($fullPath, $data, $this);
            // phpcs:enable Inpsyde.CodeQuality.VariablesName.SnakeCaseVar


            return ob_get_clean() ?: '';
        } catch (Throwable $throwable) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            if (!$isDebug) {
                return '';
            }

            throw new RuntimeException(
                sprintf('Could not render template %s', $templateName),
                0,
                $throwable
            );
        }
    }
}
