<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class MultilingualListTest extends UnitTestCase
{
    public function testFromArray(): void
    {
        $array = [
            [
                'id' => 'term:17',
                'de' => 'Keyword 1',
                'en' => 'Keyword 1 EN',
            ],
            [
                'id' => 'term:18',
                'de' => 'Keyword 2',
                'en' => 'Keyword 2 EN',
            ],
        ];

        $sut = MultilingualList::fromArray($array);
        $this->assertSame($array, $sut->asArray());
    }
}
