<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class MultilingualLinksTest extends UnitTestCase
{
    public function testFromArray(): void
    {
        $array = [
            [
                'id' => 'term:3',
                'name' => [
                    'id' => 'term:3',
                    'de' => 'Biology',
                    'en' => 'Biology EN',
                ],
                'link_text' => [
                    'id' => 'term:3',
                    'de' => 'Link Biology',
                    'en' => 'Link Biology',
                ],
                'link_url' => [
                    'id' => 'term:3',
                    'de' => 'https://fau.localhost/biology',
                    'en' => 'https://fau.localhost/biology-en',
                ],
            ],
            [
                'id' => 'term:38',
                'name' => [
                    'id' => 'term:38',
                    'de' => 'Math',
                    'en' => 'Math EN',
                ],
                'link_text' => [
                    'id' => 'term:38',
                    'de' => 'Link Math',
                    'en' => 'Link Math EN',
                ],
                'link_url' => [
                    'id' => 'term:38',
                    'de' => 'https://fau.localhost/biology-math',
                    'en' => 'https://fau.localhost/biology-math-en',
                ],
            ],
        ];

        $sut = MultilingualLinks::fromArray($array);
        $this->assertSame($array, $sut->asArray());
    }
}
