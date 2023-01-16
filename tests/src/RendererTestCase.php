<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\DirectoryLocator;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\TemplateRenderer;
use PHPUnit\Framework\TestCase;

abstract class RendererTestCase extends TestCase
{
    protected TemplateRenderer $sut;

    public function setUp(): void
    {
        parent::setUp();

        require_once ABSPATH . WPINC . '/functions.php';

        $this->sut = TemplateRenderer::new(
            DirectoryLocator::new(TEMPLATES_DIR),
        );
    }

    protected function assertHtmlEqual(string $expected, string $actual): void
    {
        $this->assertSame(
            $this->normalizeSpaces($expected),
            $this->normalizeSpaces($actual)
        );
    }

    private function normalizeSpaces(string $html): string
    {
        return (string) preg_replace('/[\s]{2,}|\n/', '', $html);
    }
}
