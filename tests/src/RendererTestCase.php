<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests;

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\DirectoryLocator;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\TemplateRenderer;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;

abstract class RendererTestCase extends WpDbLessTestCase
{
    use AsserHtmlTrait;

    protected TemplateRenderer $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = TemplateRenderer::new(
            DirectoryLocator::new(TEMPLATES_DIR),
        );
    }
}
