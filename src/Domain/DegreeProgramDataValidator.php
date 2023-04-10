<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

interface DegreeProgramDataValidator
{
    /**
     * @param array<string, mixed> $data
     * @return Violations Violations array
     */
    public function validate(array $data): Violations;
}
