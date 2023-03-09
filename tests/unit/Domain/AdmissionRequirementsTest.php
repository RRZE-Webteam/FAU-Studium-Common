<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class AdmissionRequirementsTest extends UnitTestCase
{
    public function testFromArray(): void
    {
        $data = [
            'bachelor_or_teaching_degree' => [
                'id' => 'term:5',
                'name' => [
                    'id' => 'term:5',
                    'de' => 'Name Bachelor DE',
                    'en' => 'Name Bachelor EN',
                ],
                'link_text' => [
                    'id' => 'term:5',
                    'de' => 'Link Text Bachelor DE',
                    'en' => 'Link Text Bachelor EN',
                ],
                'link_url' => [
                    'id' => 'term:5',
                    'de' => 'Link URL Bachelor DE',
                    'en' => 'Link URL Bachelor EN',
                ],
                'parent' => null,
            ],
            'teaching_degree_higher_semester' => [
                'id' => 'term:6',
                'name' => [
                    'id' => 'term:6',
                    'de' => 'Name Higher Semester DE',
                    'en' => 'Name Higher Semester EN',
                ],
                'link_text' => [
                    'id' => 'term:6',
                    'de' => 'Link Text Higher Semester DE',
                    'en' => 'Link Text Higher Semester EN',
                ],
                'link_url' => [
                    'id' => 'term:6',
                    'de' => 'Link URL Higher Semester DE',
                    'en' => 'Link URL Higher Semester EN',
                ],
                'parent' => [
                    'id' => 'term:123',
                    'name' => [
                        'id' => 'term:123',
                        'de' => 'Frei',
                        'en' => 'Free',
                    ],
                    'link_text' => [
                        'id' => 'term:123',
                        'de' => '',
                        'en' => '',
                    ],
                    'link_url' => [
                        'id' => 'term:123',
                        'de' => '',
                        'en' => '',
                    ],
                    'parent' => null,
                ],
            ],
            'master' => [
                'id' => 'term:7',
                'name' => [
                    'id' => 'term:7',
                    'de' => 'Name Master DE',
                    'en' => 'Name Master EN',
                ],
                'link_text' => [
                    'id' => 'term:7',
                    'de' => 'Link Text Master DE',
                    'en' => 'Link Text Master EN',
                ],
                'link_url' => [
                    'id' => 'term:7',
                    'de' => 'Link URL Master DE',
                    'en' => 'Link URL Master EN',
                ],
                'parent' => null,
            ],
        ];

        $sut = AdmissionRequirements::fromArray($data);
        $this->assertSame($data, $sut->asArray());
        $this->assertSame(
            'Name Bachelor EN',
            $sut->bachelorOrTeachingDegree()->name()->asString('en')
        );
        $this->assertSame(
            'Name Higher Semester DE',
            $sut->teachingDegreeHigherSemester()->name()->asString('de')
        );
        $this->assertSame(
            'Name Master DE',
            $sut->master()->name()->asString('de')
        );
    }
}
