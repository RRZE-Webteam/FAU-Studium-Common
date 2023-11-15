<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\PostType;

class DegreeProgramPostType
{
    public const KEY = 'studiengang';
    public const REST_BASE = 'degree-program';

    private function __construct(
        private array $args
    ) {
    }

    private static function default(): self
    {
        return new self(
            [
                'label' => _x(
                    'Degree Programs',
                    'backoffice: post type label',
                    'fau-degree-program-common'
                ),
                'labels' => self::labels(),
                'hierarchical' => false,
                'supports' => [
                    'editor',
                    'author',
                ],
            ]
        );
    }

    public static function public(): self
    {
        return self::default()->merge(
            [
                'public' => true,
                'show_in_rest' => true,
                'rest_base' => self::REST_BASE,
                'menu_icon' => 'dashicons-welcome-learn-more',
            ]
        );
    }

    public static function hidden(): self
    {
        return self::default()->merge(
            [
                'public' => false,
                'publicly_queryable' => true,
                'show_in_rest' => false,
            ]
        );
    }

    /**
     * @return array<string, string>
     * @phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    private static function labels(): array
    {
        return [
            'name' => _x(
                'Degree Programs',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'singular_name' => _x(
                'Degree Program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'add_new' => _x(
                'Add New Degree Program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'add_new_item' => _x(
                'Add New Degree Program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'edit_item' => _x(
                'Edit Degree Program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'new_item' => _x(
                'New Degree Program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'view_item' => _x(
                'View Degree Program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'view_items' => _x(
                'View Degree Programs',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'search_items' => _x(
                'Search Degree Programs',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'not_found' => _x(
                'No degree programs found.',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'not_found_in_trash' => _x(
                'No degree programs found in Trash.',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'all_items' => _x(
                'All Degree Programs',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'archives' => _x(
                'Degree Program Archives',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'attributes' => _x(
                'Degree Program Attributes',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'insert_into_item' => _x(
                'Insert into degree program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'uploaded_to_this_item' => _x(
                'Uploaded to this degree program',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'filter_items_list' => _x(
                'Filter degree programs list',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'items_list_navigation' => _x(
                'Degree programs list navigation',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'items_list' => _x(
                'Degree programs list',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'item_published' => _x(
                'Degree program published.',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'item_published_privately' => _x(
                'Degree program published privately.',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'item_reverted_to_draft' => _x(
                'Degree program reverted to draft.',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'item_scheduled' => _x(
                'Degree program scheduled.',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'item_updated' => _x(
                'Degree program updated.',
                'backoffice: post type label',
                'fau-degree-program-common'
            ),
            'item_link' => _x(
                'Degree Program Link.',
                'backoffice: navigation link block title',
                'fau-degree-program-common'
            ),
            'item_link_description' => _x(
                'A link to a degree program.',
                'backoffice: navigation link block description',
                'fau-degree-program-common'
            ),
        ];
    }

    public function args(): array
    {
        return $this->args;
    }

    public function key(): string
    {
        return self::KEY;
    }

    public function merge(array $args): self
    {
        return new self(array_merge($this->args, $args));
    }
}
