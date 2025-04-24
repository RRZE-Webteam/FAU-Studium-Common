<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

interface DegreeProgramSanitizer
{
    public const ALLOWED_ENTITIES = [
        'img' => [
            'title' => [],
            'src' => [],
            'alt' => [],
            'srcset' => [],
            'sizes' => [],
        ],
        'picture' => [
            'title' => [],
            'src' => [],
            'alt' => [],
        ],
        'figure' => [
            'title' => [],
            'src' => [],
            'alt' => [],
        ],
        'a' => [
            'title' => [],
            'href' => [],
            'target' => [],
        ],
        'blockquote' => [
            'class' => [],
        ],
        'cite' => [],
        'br' => [],
        'p' => [],
        'strong' => [],
        'ul' => [],
        'ol' => [],
        'li' => [],
        'dl' => [],
        'dd' => [],
        'dt' => [],
        'h3' => [],
        'h4' => [],
        'h5' => [],
    ];

    public const ALLOWED_SHORTCODES = [
        'alert',
        'contact',
        'fau-video',
        'fachanteile',
        'faudir',
    ];

    public const ALLOWED_BLOCKS = [
        'core/heading',
        'core/image',
        'core/list',
        'core/paragraph',
        'core/shortcode',
        'core/quote',
        'fau/description-list',
        'fau/faudir-block',
        'fau-degree-program/shares',
    ];

    public function sanitizeContentField(string $content): string;
}
