<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;

interface DegreeProgramDataValidator
{
    /**
     * @param array $data
     * @return ArrayOfStrings Violations array
     */
    public function validate(array $data): ArrayOfStrings;
}
