<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use RuntimeException;

interface DegreeProgramRepository
{
    /**
     * @throws RuntimeException
     */
    public function getById(DegreeProgramId $degreeProgramId): DegreeProgram;

    /**
     * @throws RuntimeException
     */
    public function save(DegreeProgram $degreeProgram): void;
}
