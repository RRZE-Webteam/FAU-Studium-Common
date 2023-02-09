<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Stringable;
use WP_CLI;

final class WordPressCliLogger extends AbstractLogger
{
    public function __construct(
        private LoggerInterface $decorated,
    ) {
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     */
    public function log($level, Stringable|string $message, array $context = []): void
    {
        $isCli = defined('WP_CLI') && WP_CLI;

        if ($isCli) {
            /** @var WP_CLI\Runner $runner */
            $runner = WP_CLI::get_runner();
            $context['wp_cli'] = $runner->arguments;
        }

        $this->decorated->log($level, $message, $context);

        if (!$isCli) {
            return;
        }

        self::outputToConsole((string) $message, (string) $level);
    }

    private static function outputToConsole(string $message, string $level): void
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
                WP_CLI::error($message);
                break;
            case LogLevel::ERROR:
                WP_CLI::error($message, false);
                break;
            case LogLevel::WARNING:
                WP_CLI::warning($message);
                break;
            case LogLevel::DEBUG:
                WP_CLI::debug($message);
                break;
            case LogLevel::INFO:
                WP_CLI::success($message);
                break;
            default:
                WP_CLI::log($message);
        }
    }
}
