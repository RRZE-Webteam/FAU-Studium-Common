<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
interface DegreeProgramViewRepository
{
    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw;

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    public function findTranslated(
        DegreeProgramId $degreeProgramId,
        string $languageCode
    ): ?DegreeProgramViewTranslated;

    /**
     * @psalm-return PaginationAwareCollection<DegreeProgramViewRaw>
     */
    public function findRawCollection(CollectionCriteria $criteria): PaginationAwareCollection;

    /**
     * @psalm-param LanguageCodes $languageCode
     * @psalm-return PaginationAwareCollection<DegreeProgramViewTranslated>
     */
    public function findTranslatedCollection(
        CollectionCriteria $criteria,
        string $languageCode
    ): PaginationAwareCollection;
}
