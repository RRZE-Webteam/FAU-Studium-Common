<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

class ContentTest extends UnitTestCase
{
    public function testFromArray(): void
    {
        $data = [
            'about' => [
                'title' => [
                    'id' => 'option:about',
                    'de' => 'About Title DE',
                    'en' => 'About Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'About Description DE',
                    'en' => 'About Description EN',
                ],
            ],
            'structure' => [
                'title' => [
                    'id' => 'option:structure',
                    'de' => 'Structure Title DE',
                    'en' => 'Structure Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'Structure Description DE',
                    'en' => 'Structure Description EN',
                ],
            ],
            'specializations' => [
                'title' => [
                    'id' => 'option:specializations',
                    'de' => 'Specializations Title DE',
                    'en' => 'Specializations Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'Specializations Description DE',
                    'en' => 'Specializations Description EN',
                ],
            ],
            'qualities_and_skills' => [
                'title' => [
                    'id' => 'option:qualities_and_skills',
                    'de' => 'Qualities Title DE',
                    'en' => 'Qualities Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'Qualities Description DE',
                    'en' => 'Qualities Description EN',
                ],
            ],
            'why_should_study' => [
                'title' => [
                    'id' => 'option:why_should_study',
                    'de' => 'Why Title DE',
                    'en' => 'Why Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'Why Description DE',
                    'en' => 'Why Description EN',
                ],
            ],
            'career_prospects' => [
                'title' => [
                    'id' => 'option:career_prospects',
                    'de' => 'Career Title DE',
                    'en' => 'Career Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'Career Description DE',
                    'en' => 'Career Description EN',
                ],
            ],
            'special_features' => [
                'title' => [
                    'id' => 'option:special_features',
                    'de' => 'Special Title DE',
                    'en' => 'Special Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'Special Description DE',
                    'en' => 'Special Description EN',
                ],
            ],
            'testimonials' => [
                'title' => [
                    'id' => 'option:testimonials',
                    'de' => 'Testimonials Title DE',
                    'en' => 'Testimonials Title EN',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'Testimonials Description DE',
                    'en' => 'Testimonials Description EN',
                ],
            ],
        ];

        $sut = Content::fromArray($data);
        $this->assertSame($data, $sut->asArray());
        $this->assertSame(
            'About Title DE',
            $sut->about()->title()->asString('de')
        );
        $this->assertSame(
            'Structure Description EN',
            $sut->structure()->description()->asString('en')
        );
        $this->assertSame(
            'Specializations Title EN',
            $sut->specializations()->title()->asString('en')
        );
        $this->assertSame(
            'Qualities Description DE',
            $sut->qualitiesAndSkills()->description()->asString('de')
        );
        $this->assertSame(
            'option:why_should_study',
            $sut->whyShouldStudy()->title()->id()
        );
        $this->assertSame(
            'Career Title DE',
            $sut->careerProspects()->title()->inGerman()
        );
        $this->assertSame(
            'Special Title EN',
            $sut->specialFeatures()->title()->inEnglish()
        );
        $this->assertSame(
            'Testimonials Description DE',
            $sut->testimonials()->description()->inGerman()
        );
    }
}
