<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Cache;

use Fau\DegreeProgram\Common\Application\Event\CacheInvalidated;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @psalm-import-type Reason from CacheInvalidated
 */
final class CacheInvalidator
{
    public function __construct(
        private CacheKeyGenerator $cacheKeyGenerator,
        private CacheInterface $cache,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @psalm-param Reason $reason
     */
    public function invalidateFully(string $reason = CacheInvalidated::ENFORCED): bool
    {
        $result = $this->cache->clear();
        if (!$result) {
            $this->logger->error('Failed degree program full cache invalidation.');
            return false;
        }

        $this->logger->info('Successful degree program full cache invalidation.');
        $this->eventDispatcher->dispatch(CacheInvalidated::fully($reason));
        return true;
    }

    /**
     * @psalm-param array<int> $ids
     * @psalm-param Reason $reason
     *
     * @throws InvalidArgumentException
     */
    public function invalidatePartially(array $ids, string $reason = CacheInvalidated::ENFORCED): bool
    {
        if (count($ids) === 0) {
            $this->logger->debug(
                'Skipped degree program partial cache invalidation because no IDs were provided.'
            );
            return true;
        }

        $result = [];

        $keys = [];
        foreach ($ids as $id) {
            $keys[] = $this->cacheKeyGenerator->generateForDegreeProgram(
                DegreeProgramId::fromInt($id)
            );

            $keys[] = $this->cacheKeyGenerator->generateForDegreeProgram(
                DegreeProgramId::fromInt($id),
                CacheKeyGenerator::TRANSLATED_TYPE
            );
        }
        $result[] = $this->cache->deleteMultiple($keys);

        $wasSuccessful = !in_array(false, $result, true);

        if (!$wasSuccessful) {
            $this->logger->error(
                sprintf(
                    'Failed degree program partial cache invalidation for IDs: %s.',
                    implode(', ', $ids)
                )
            );
            return false;
        }

        $this->logger->info(
            sprintf(
                'Successful degree program partial cache invalidation for IDs: %s.',
                implode(', ', $ids)
            )
        );
        $this->eventDispatcher->dispatch(CacheInvalidated::partially($ids, $reason));

        return true;
    }
}
