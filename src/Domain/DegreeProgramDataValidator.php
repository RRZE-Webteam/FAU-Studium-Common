<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

interface DegreeProgramDataValidator
{
    /**
     * @param array<string, mixed> $data
     * @return Violations Violations array
     */
    public function validatePublish(array $data): Violations;

    /**
     * @param array<string, mixed> $data
     * @return Violations Violations array
     */
    public function validateDraft(array $data): Violations;
}
