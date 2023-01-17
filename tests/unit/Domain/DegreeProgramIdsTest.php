<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\DegreeProgramIds;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class DegreeProgramIdsTest extends UnitTestCase
{
    public function testFromArray(): void
    {
        $array = [1, 4, 123];
        $sut = DegreeProgramIds::fromArray($array);
        $this->assertSame($array, $sut->asArray());
    }
}
