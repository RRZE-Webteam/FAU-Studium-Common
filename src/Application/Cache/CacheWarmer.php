<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Cache;

use Fau\DegreeProgram\Common\Application\Event\CacheWarmed;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

final class CacheWarmer
{
    public function __construct(
        private CacheKeyGenerator $cacheKeyGenerator,
        private CacheInterface $cache,
        private DegreeProgramCollectionRepository $collectionRepository,
        private EventDispatcherInterface $eventDispatcher,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function warmFully(): bool
    {
        $criteria = CollectionCriteria::new()
            ->withPerPage(-1);

        $result = $this->warm($criteria);

        if (!$result) {
            $this->logger->error('Failed degree program full cache warming.');
            return false;
        }

        $this->logger->info('Successful degree program full cache warming.');
        $this->eventDispatcher->dispatch(CacheWarmed::fully());

        return true;
    }

    /**
     * @psalm-param array<int> $ids
     *
     * @throws InvalidArgumentException
     */
    public function warmPartially(array $ids): bool
    {
        if (count($ids) === 0) {
            $this->logger->debug(
                'Skipped degree program partial cache warming because no IDs were provided.'
            );
            return true;
        }

        $criteria = CollectionCriteria::new()
            ->withPerPage(-1)
            ->withInclude($ids);

        $result = $this->warm($criteria);

        if (!$result) {
            $this->logger->error(
                sprintf(
                    'Failed degree program cache partial warming for IDs: %s.',
                    implode(', ', $ids)
                )
            );
            return false;
        }

        $this->logger->info(
            sprintf(
                'Successful degree program partial cache warming for IDs: %s.',
                implode(', ', $ids)
            )
        );
        $this->eventDispatcher->dispatch(CacheWarmed::partially($ids));

        return true;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function warm(CollectionCriteria $criteria): bool
    {
        $result = [];

        $rawCollection = $this->collectionRepository->findRawCollection($criteria);
        if (!$rawCollection instanceof PaginationAwareCollection) {
            return false;
        }

        $values = [];
        foreach ($rawCollection as $item) {
            $key = $this->cacheKeyGenerator->generateForDegreeProgram($item->id());
            $values[$key] = $item->asArray();
        }
        $result[] = $this->cache->setMultiple($values);

        $translatedCollection = $this->collectionRepository->findTranslatedCollection(
            $criteria,
            MultilingualString::DE
        );
        if (!$translatedCollection instanceof PaginationAwareCollection) {
            return false;
        }

        $values = [];
        foreach ($translatedCollection as $item) {
            $key = $this->cacheKeyGenerator->generateForDegreeProgram(
                DegreeProgramId::fromInt($item->id()),
                CacheKeyGenerator::TRANSLATED_TYPE
            );

            $values[$key] = $item->asArray();
        }
        $result[] = $this->cache->setMultiple($values);

        return !in_array(false, $result, true);
    }
}
