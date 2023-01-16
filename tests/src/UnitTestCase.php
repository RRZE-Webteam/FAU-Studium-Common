<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

abstract class UnitTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
        Monkey\Functions\stubTranslationFunctions();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
