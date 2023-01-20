<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\TemplateRenderer;

use Fau\DegreeProgram\Common\Tests\RendererTestCase;

class BasicRenderingTest extends RendererTestCase
{
    public function testProductionMode(): void
    {
        $this->assertSame(
            '',
            $this->sut->render('wrong', ['hello' => 'Hello World!'])
        );
    }
}
