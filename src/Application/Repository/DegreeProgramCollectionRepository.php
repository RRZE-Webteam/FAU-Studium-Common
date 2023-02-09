<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Repository;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
interface DegreeProgramCollectionRepository
{
    /**
     * @psalm-return PaginationAwareCollection<DegreeProgramViewRaw>|null
     *               Null in case we could not find the collection for external reasons.
     */
    public function findRawCollection(CollectionCriteria $criteria): ?PaginationAwareCollection;

    /**
     * @psalm-param LanguageCodes $languageCode
     * @psalm-return PaginationAwareCollection<DegreeProgramViewTranslated>|null
     *               Null in case we could not find the collection for external reasons.
     */
    public function findTranslatedCollection(
        CollectionCriteria $criteria,
        string $languageCode
    ): ?PaginationAwareCollection;
}
