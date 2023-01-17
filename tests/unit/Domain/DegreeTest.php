<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

final class DegreeTest extends UnitTestCase
{
    public function testEmpty(): void
    {
        $sut = Degree::empty();
        $this->assertSame(
            '{"id":"","name":{"id":"","de":"","en":""},"abbreviation":{"id":"","de":"","en":""}}',
            json_encode($sut)
        );
    }

    public function testFromArray(): void
    {
        $data = [
            'id' => 'term:6',
            'name' => [
                'id' => 'term:5',
                'de' => 'Lehramt Mittelschule',
                'en' => 'Teaching secondary education',
            ],
            'abbreviation' => [
                'id' => 'term:5',
                'de' => 'LM',
                'en' => 'TSE',
            ],
        ];

        $sut = Degree::fromArray($data);
        $this->assertSame($data, $sut->asArray());
        $this->assertSame(
            'Lehramt Mittelschule',
            $sut->name()->inGerman()
        );
        $this->assertSame(
            'Teaching secondary education',
            $sut->name()->inEnglish()
        );
        $this->assertSame(
            'LM',
            $sut->abbreviation()->inGerman()
        );
        $this->assertSame(
            'TSE',
            $sut->abbreviation()->inEnglish()
        );
    }

    /**
     * @dataProvider degreeAsStringDataProvider
     */
    public function testFullDegreeAsString(Degree $degree, string $de, string $en): void
    {
        $this->assertSame(
            $de,
            $degree->asString('de')
        );
        $this->assertSame(
            $en,
            $degree->asString('en')
        );
        $this->assertEmpty($degree->asString('not_supported_code'));
    }

    public function degreeAsStringDataProvider(): iterable
    {
        yield [
            'full_degree' =>
                Degree::new(
                    'term:5',
                    MultilingualString::fromTranslations(
                        'term:5',
                        'Lehramt Mittelschule',
                        'Teaching secondary education'
                    ),
                    MultilingualString::fromTranslations(
                        'term:5',
                        'LM',
                        'TSE'
                    )
                ),
            'LM: Lehramt Mittelschule',
            'TSE: Teaching secondary education',
        ];

        yield [
            'without_name' =>
                Degree::new(
                    'term:5',
                    MultilingualString::fromTranslations(
                        'term:5',
                        '',
                        ''
                    ),
                    MultilingualString::fromTranslations(
                        'term:5',
                        'LM',
                        'TSE'
                    )
                ),
            '',
            '',
        ];

        yield [
            'without_abbreviation' =>
                Degree::new(
                    'term:5',
                    MultilingualString::fromTranslations(
                        'term:5',
                        'Lehramt Mittelschule',
                        'Teaching secondary education'
                    ),
                    MultilingualString::fromTranslations(
                        'term:5',
                        '',
                        ''
                    )
                ),
            'Lehramt Mittelschule',
            'Teaching secondary education',
        ];

        yield [
            'empty' =>
                Degree::empty(),
            '',
            '',
        ];
    }
}
