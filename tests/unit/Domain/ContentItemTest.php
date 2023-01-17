<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\ContentItem;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use PHPUnit\Framework\TestCase;

class ContentItemTest extends TestCase
{
    public function testFromArray(): void
    {
        $data = [
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
        ];

        $sut = ContentItem::fromArray($data);
        $this->assertSame(
            $data,
            $sut->asArray(),
        );

        $this->assertSame(
            'About Title DE',
            $sut->title()->inGerman(),
        );
        $this->assertSame(
            'About Description EN',
            $sut->description()->inEnglish(),
        );
    }

    public function testDefault(): void
    {
        $data = [
            'title' => [
                'id' => 'option:original',
                'de' => 'About DE Original',
                'en' => '',
            ],
            'description' => [
                'id' => 'post_meta:23',
                'de' => 'About Description DE',
                'en' => 'About Description EN',
            ],
        ];

        $sut = ContentItem::fromArray($data);
        $sut = $sut->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'About DE Default',
                'About EN Default'
            )
        );
        $this->assertSame(
            [
                'title' => [
                    'id' => 'option:original',
                    'de' => 'About DE Original',
                    'en' => 'About EN Default',
                ],
                'description' => [
                    'id' => 'post_meta:23',
                    'de' => 'About Description DE',
                    'en' => 'About Description EN',
                ],
            ],
            $sut->asArray()
        );
    }
}
