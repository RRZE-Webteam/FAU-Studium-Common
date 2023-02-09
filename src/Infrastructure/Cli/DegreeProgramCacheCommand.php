<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Cli;

use Fau\DegreeProgram\Common\Application\Cache\CacheWarmer;
use Throwable;
use WP_CLI;

final class DegreeProgramCacheCommand
{
    public function __construct(private CacheWarmer $cacheWarmer)
    {
    }

    /**
     * Warm degree programs cache.
     *
     * ## EXAMPLES
     *
     *     wp fau cache warm
     *
     * @when after_wp_load
     */
    public function warm(): void
    {
        try {
            $this->cacheWarmer->warmFully();
        } catch (Throwable $exception) {
            WP_CLI::error($exception);
        }
    }
}
