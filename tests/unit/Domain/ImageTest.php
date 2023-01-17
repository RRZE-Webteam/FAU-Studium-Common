<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class ImageTest extends UnitTestCase
{
    public function testEmpty(): void
    {
        $sut = Image::empty();
        $this->assertEmpty($sut->id());
        $this->assertEmpty($sut->url());
    }

    public function testFromArray(): void
    {
        $array = [
            'id' => 5,
            'url' => 'https://fau.localhost/wp-content/uploads/2022/12/abstract-1-1528080.jpg',
        ];

        $sut = Image::fromArray($array);
        $this->assertSame($array, $sut->asArray());
    }
}
