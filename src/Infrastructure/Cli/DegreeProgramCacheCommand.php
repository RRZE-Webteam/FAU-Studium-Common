<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Cli;

use Fau\DegreeProgram\Common\Application\Cache\CacheInvalidator;
use Throwable;
use WP_CLI;

final class DegreeProgramCacheCommand
{
    public function __construct(private CacheInvalidator $cacheInvalidator)
    {
    }

    /**
     * Warm degree programs cache.
     *
     * ## EXAMPLES
     *
     *     wp fau cache invalidate
     *
     * @when after_wp_load
     */
    public function invalidate(): void
    {
        try {
            $this->cacheInvalidator->invalidateFully();
        } catch (Throwable $exception) {
            WP_CLI::error($exception);
        }
    }
}
