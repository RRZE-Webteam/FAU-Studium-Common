<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class DegreeProgramIdTest extends UnitTestCase
{
    public function testFromInt(): void
    {
        $id = 23;
        $sut = DegreeProgramId::fromInt($id);
        $this->assertSame($id, $sut->asInt());
    }
}
