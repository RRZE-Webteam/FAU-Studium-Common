<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use PHPUnit\Framework\TestCase;

class NumberOfStudentsTest extends TestCase
{
    public function testFromArray(): void
    {
        $array = [
            'id' => 'term:16',
            'name' => '>200',
            'description' => 'Many',
        ];

        $sut = NumberOfStudents::fromArray($array);
        $this->assertSame($array, $sut->asArray());
        $this->assertSame('Many', $sut->description());
        $this->assertSame('>200', $sut->asString());
        $this->assertSame('term:16', $sut->id());
    }
}
