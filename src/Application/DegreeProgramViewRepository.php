<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\DegreeProgramId;

interface DegreeProgramViewRepository
{
    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw;

    /**
     * @psalm-param 'de'|'en' $languageCode
     */
    public function findTranslated(
        DegreeProgramId $degreeProgramId,
        string $languageCode
    ): ?DegreeProgramViewTranslated;
}
