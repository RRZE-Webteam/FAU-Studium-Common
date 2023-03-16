<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
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
        if ($level === LogLevel::DEBUG && !$this->isDebugMode()) {
            return;
        }

        // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
        error_log(
            $this->prepareLogEntry((string) $level, (string) $message, $context)
        );

        $context['plugin'] = $this->package;
        do_action(
            self::buildRrzeLogActionName((string) $level),
            (string) $message,
            $context
        );
    }

    private function prepareLogEntry(string $level, string $message, array $context): string
    {
        $parts = [
            sprintf('[%s] [%s]: %s', strtoupper($level), $this->package, $message),
        ];

        if (isset($context['exception']) && $context['exception'] instanceof Throwable) {
            $exception = $context['exception'];
            unset($context['exception']);

            $parts[] = $exception->getMessage();
            $parts[] = $exception->getTraceAsString();
        }

        $context['site_url'] = home_url();

        $parts[] = json_encode($context);

        return implode("\n", $parts);
    }

    private function isDebugMode(): bool
    {
        /** @psalm-suppress TypeDoesNotContainType */
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    /**
     * @link https://gitlab.rrze.fau.de/rrze-webteam/rrze-log
     */
    private static function buildRrzeLogActionName(string $level): string
    {
        $rrzeLevel = match ($level) {
            LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR => 'error',
            LogLevel::WARNING => 'warning',
            LogLevel::NOTICE => 'notice',
            default => 'info',
        };

        return 'rrze.log.' . $rrzeLevel;
    }
}
