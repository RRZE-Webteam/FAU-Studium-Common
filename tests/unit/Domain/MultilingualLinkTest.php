<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use PHPUnit\Framework\TestCase;

class MultilingualLinkTest extends TestCase
{
    public function testFromArray(): void
    {
        $array = [
            'id' => 'term:11',
            'name' => [
                'id' => 'term:11',
                'de' => 'Faculty Math',
                'en' => 'Faculty Math EN',
            ],
            'link_text' => [
                'id' => 'term:11',
                'de' => 'Link Faculty Math',
                'en' => 'Link Faculty Math EN',
            ],
            'link_url' => [
                'id' => 'term:11',
                'de' => 'https://fau.localhost/faculty-math',
                'en' => 'https://fau.localhost/faculty-math-en',
            ],
        ];

        $sut = MultilingualLink::fromArray($array);
        $this->assertSame($array, $sut->asArray());
    }
}
