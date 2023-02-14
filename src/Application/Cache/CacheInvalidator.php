<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Cache;

use Fau\DegreeProgram\Common\Application\Event\CacheInvalidated;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class CacheInvalidator
{
    public function __construct(
        private CacheKeyGenerator $cacheKeyGenerator,
        private CacheInterface $cache,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
    ) {
    }

    public function invalidateFully(): bool
    {
        $result = $this->cache->clear();
        if (!$result) {
            $this->logger->error('Failed degree program full cache invalidation.');
            return false;
        }

        $this->eventDispatcher->dispatch(CacheInvalidated::fully());
        $this->logger->info('Successful degree program full cache invalidation.');
        return true;
    }

    /**
     * @psalm-param array<int> $ids
     *
     * @throws InvalidArgumentException
     */
    public function invalidatePartially(array $ids): bool
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

        $this->eventDispatcher->dispatch(CacheInvalidated::partially($ids));
        $this->logger->info(
            sprintf(
                'Successful degree program partial cache invalidation for IDs: %s.',
                implode(', ', $ids)
            )
        );

        return true;
    }
}
