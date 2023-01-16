<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer;

interface Locator
{
    /**
     * @param string $templateName
     * @return string Full path
     */
    public function locate(string $templateName): string;
}
