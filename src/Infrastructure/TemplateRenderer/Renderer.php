<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer;

interface Renderer
{
    /**
     * Return template content
     */
    public function render(string $templateName, array $data = []): string;
}
