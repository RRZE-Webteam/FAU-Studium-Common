<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Content;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

final class PostTypeTest extends UnitTestCase
{
    public function testPostTypeArgsGeneration(): void
    {
        $publicArgs = DegreeProgramPostType::public()->args();
        unset($publicArgs['labels']);
        $this->assertSame(
            [
                'label' => 'Degree Programs',
                'hierarchical' => false,
                'supports' => [
                    'editor',
                    'author',
                ],
                'public' => true,
                'show_in_rest' => true,
                'rest_base' => 'degree-program',
                'menu_icon' => 'dashicons-welcome-learn-more',
            ],
            $publicArgs
        );

        $hiddenArgs = DegreeProgramPostType::hidden()->args();
        unset($hiddenArgs['labels']);
        $this->assertSame(
            [
                'label' => 'Degree Programs',
                'hierarchical' => false,
                'supports' => [
                    'editor',
                    'author',
                ],
                'public' => false,
                'publicly_queryable' => true,
                'show_in_rest' => false,
            ],
            $hiddenArgs
        );
    }

    public function testMerging(): void
    {
        $args = DegreeProgramPostType::public()
            ->merge([
                'public' => false,
                'template' => [],
            ])
            ->args();

        $this->assertFalse($args['public']);
        $this->assertSame([], $args['template']);
    }
}
