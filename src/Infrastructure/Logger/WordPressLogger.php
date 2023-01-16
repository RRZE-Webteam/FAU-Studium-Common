<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Logger;

use Psr\Log\AbstractLogger;
use Stringable;
use Throwable;

final class WordPressLogger extends AbstractLogger
{
    private function __construct(
        private string $package
    ) {
    }

    public static function new(string $package): self
    {
        return new self($package);
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     */
    public function log($level, Stringable|string $message, array $context = []): void
    {
        if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
            error_log(
                $this->prepareLogEntry((string) $level, (string) $message, $context)
            );
        }

        $context['message'] = $message;
        do_action(
            // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
            sprintf('%s.%s', $this->package, (string) $level),
            $context
        );
    }

    private function prepareLogEntry(string $level, string $message, array $context): string
    {
        $parts = [
            sprintf('[%s]: %s', strtoupper($level), $message),
        ];

        if (isset($context['exception']) && $context['exception'] instanceof Throwable) {
            $exception = $context['exception'];
            unset($context['exception']);

            $parts[] = $exception->getMessage();
            $parts[] = $exception->getTraceAsString();
        }

        $parts[] = json_encode($context);

        return implode("\n", $parts);
    }
}
