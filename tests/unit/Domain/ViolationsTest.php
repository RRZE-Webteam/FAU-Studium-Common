<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\Violation;
use Fau\DegreeProgram\Common\Domain\Violations;
use PHPUnit\Framework\TestCase;

class ViolationsTest extends TestCase
{
    public function testAdd(): void
    {
        $violations = Violations::new();
        $violations->add(
            Violation::new('path1', 'message1', 'code1'),
            Violation::new('path2', 'message2', 'code2'),
        );

        $this->assertSame($violations['path1']->path(), 'path1');
        $this->assertCount(2, $violations);
        $this->assertSame([
            'path1' => [
                'path' => 'path1',
                'errorMessage' => 'message1',
                'errorCode' => 'code1',
            ],
            'path2' => [
                'path' => 'path2',
                'errorMessage' => 'message2',
                'errorCode' => 'code2',
            ],
        ], $violations->asArray());
    }
}
