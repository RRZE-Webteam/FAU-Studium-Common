<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\LanguageExtension;

/**
 * @template-extends ArrayChangeset<int>
 */
final class IntegersListChangeset extends ArrayChangeset
{
    /**
     * @return array<int>
     */
    public function added(): array
    {
        return array_values(parent::added());
    }

    /**
     * @return array<int>
     */
    public function removed(): array
    {
        return array_values(parent::removed());
    }
}
