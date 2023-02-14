<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Psr\SimpleCache\CacheInterface;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-import-type DegreeProgramViewRawArrayType from DegreeProgramViewRaw
 * @psalm-import-type DegreeProgramViewTranslatedArrayType from DegreeProgramViewTranslated
 */
final class CachedDegreeProgramViewRepository implements DegreeProgramViewRepository
{
    public function __construct(
        private DegreeProgramViewRepository $decorated,
        private CacheKeyGenerator $cacheKeyGenerator,
        private CacheInterface $cache,
    ) {
    }

    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw
    {
        $key = $this->cacheKeyGenerator->generateForDegreeProgram($degreeProgramId);
        /** @psalm-var ?DegreeProgramViewRawArrayType $data */
        $data = $this->cache->get($key);

        return is_array($data)
            ? DegreeProgramViewRaw::fromArray($data)
            : $this->decorated->findRaw($degreeProgramId);
    }

    public function findTranslated(DegreeProgramId $degreeProgramId, string $languageCode): ?DegreeProgramViewTranslated
    {
        $key = $this->cacheKeyGenerator->generateForDegreeProgram($degreeProgramId, CacheKeyGenerator::TRANSLATED_TYPE);
        /** @psalm-var ?DegreeProgramViewTranslatedArrayType $data */
        $data = $this->cache->get($key);

        if (!is_array($data)) {
            return $this->decorated->findTranslated($degreeProgramId, $languageCode);
        }

        return DegreeProgramViewTranslated::fromArray($data)
            ->withBaseLang($languageCode);
    }
}
