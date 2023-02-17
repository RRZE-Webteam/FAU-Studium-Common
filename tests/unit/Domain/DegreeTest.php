<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

final class DegreeTest extends UnitTestCase
{
    public function testEmpty(): void
    {
        $sut = Degree::empty();
        $this->assertSame(
            '{"id":"","name":{"id":"","de":"","en":""},"abbreviation":{"id":"","de":"","en":""},"parent":null}',
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
            'parent' => [
                'id' => 'term:26',
                'name' => [
                    'id' => 'term:26',
                    'de' => 'Bachelorstudiengänge',
                    'en' => 'Bachelor Degrees',
                ],
                'abbreviation' => [
                    'id' => 'term:26',
                    'de' => 'LM',
                    'en' => 'TSE',
                ],
                'parent' => null,
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
        $this->assertSame(
            'Bachelorstudiengänge',
            $sut->parent()->name()->inGerman()
        );
        $this->assertSame(
            'Bachelor Degrees',
            $sut->parent()->name()->inEnglish()
        );
    }
}
