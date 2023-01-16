<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\TemplateRenderer;

use CompileError;
use Fau\DegreeProgram\Common\Tests\RendererTestCase;
use Throwable;
use UnexpectedValueException;

class BasicRenderingTest extends RendererTestCase
{
    public function testRender(): void
    {
        $this->assertSame(
            'Hello World!',
            $this->sut->render('hello.php', ['hello' => 'Hello World!'])
        );
    }

    public function testWithoutExtension(): void
    {
        $this->assertSame(
            'Hello World!',
            $this->sut->render('hello', ['hello' => 'Hello World!'])
        );
    }

    public function testNonExistingTemplateName(): void
    {
        $exceptionHasBeenCaught = false;
        define('WP_DEBUG', true);

        try {
            $this->sut->render('wrong_name.php');
        } catch (Throwable $throwable) {
            $exceptionHasBeenCaught = true;
            $this->assertInstanceOf(CompileError::class, $throwable);

            /** @var Throwable $previous */
            $previous = $throwable->getPrevious();
            $this->assertInstanceOf(UnexpectedValueException::class, $previous);
            $this->assertStringContainsString('wrong_name.php', $previous->getMessage());
        } finally {
            $this->assertTrue($exceptionHasBeenCaught);
        }
    }

    public function testNonExistingVariables(): void
    {
        define('WP_DEBUG', true);

        $this->expectException(CompileError::class);
        $this->expectExceptionMessageMatches('/hello\.php/');
        $this->sut->render('hello.php');
    }

    public function testProductionMode(): void
    {
        define('WP_DEBUG', false);

        $this->assertSame(
            '',
            $this->sut->render('wrong', ['hello' => 'Hello World!'])
        );
    }
}
