<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class MultilingualStringTest extends UnitTestCase
{
    public function testEmpty(): void
    {
        $sut = MultilingualString::empty();
        $this->assertEmpty($sut->id());
        $this->assertEmpty($sut->inGerman());
        $this->assertEmpty($sut->inEnglish());
    }

    public function testFromArray(): void
    {
        $array = [
            'id' => 'term:17',
            'de' => 'Keyword 1',
            'en' => 'Keyword 1 EN',
        ];
        $sut = MultilingualString::fromArray($array);
        $this->assertSame($array, $sut->asArray());
    }

    public function testFromTranslations(): void
    {
        $array = [
            'id' => 'term:17',
            'de' => 'Keyword 1',
            'en' => 'Keyword 1 EN',
        ];
        $sut = MultilingualString::fromTranslations(...$array);
        $this->assertSame($array, $sut->asArray());
        $this->assertSame('term:17', $sut->id());
        $this->assertSame('Keyword 1', $sut->inGerman());
        $this->assertSame('Keyword 1', $sut->asString('de'));
        $this->assertSame('Keyword 1 EN', $sut->inEnglish());
        $this->assertSame('Keyword 1 EN', $sut->asString('en'));
    }

    public function testDefault(): void
    {
        $sut = MultilingualString::fromTranslations(
            'term:17',
            '',
            'Keyword 1 EN'
        )->mergeWithDefault(
            MultilingualString::fromTranslations(
                'default',
                'Default Keyword',
                'Default Keyword EN'
            )
        );
        $this->assertSame('term:17', $sut->id());
        $this->assertSame('Default Keyword', $sut->inGerman());
        $this->assertSame('Keyword 1 EN', $sut->inEnglish());
    }

    public function testMapTranslations(): void
    {
        $array = [
            'id' => 'term:17',
            'de' => 'Keyword 1',
            'en' => 'Keyword 1 EN',
        ];
        $data = MultilingualString::mapTranslations(
            $array,
            (static fn(string $translation) => '[Was processed]' . $translation)
        );
        $sut = MultilingualString::fromArray($data);

        $this->assertSame('term:17', $sut->id());
        $this->assertSame('[Was processed]Keyword 1', $sut->inGerman());
        $this->assertSame('[Was processed]Keyword 1 EN', $sut->inEnglish());
    }
}
