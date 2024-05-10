<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application\Filter;

final class GermanLanguageSkillsForInternationalStudentsFilter implements Filter
{
    use ArrayOfIdsFilterTrait;

    public const KEY = 'german-language-skills-for-international-students';

    public function id(): string
    {
        return self::KEY;
    }
}
