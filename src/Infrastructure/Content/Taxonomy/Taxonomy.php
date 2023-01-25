<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

abstract class Taxonomy
{
    final protected function __construct(
        private array $args
    ) {

        $this->args = array_merge($this->args, [
            'label' => $this->pluralName(),
            'labels' => self::labels(
                plural: $this->pluralName(),
                singular: $this->singularName()
            ),
            'hierarchical' => $this->isHierarchical(),
            'rest_base' => $this->restBase(),
        ]);
    }

    private static function default(): static
    {

        return new static([]);
    }

    final public static function public(): static
    {
        return self::default()->merge(
            [
                'public' => true,
                'show_in_rest' => true,
                'meta_box_cb' => false,
            ]
        );
    }

    final public static function hidden(): static
    {
        return self::default()->merge(
            [
                'public' => false,
                'show_in_rest' => false,
            ]
        );
    }

    final public function args(): array
    {
        return $this->args;
    }

    abstract public function key(): string;
    abstract public function restBase(): string;
    abstract protected function pluralName(): string;
    abstract protected function singularName(): string;
    abstract protected function isHierarchical(): bool;

    public function merge(array $args): static
    {
        return new static(array_merge($this->args, $args));
    }

    /**
     * @phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    private static function labels(string $plural, string $singular): array
    {
        return [
            'name' => $plural,
            'singular_name' => $singular,
            'search_items' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x('Search %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'popular_items' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x('Popular %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'all_items' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x('All %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'parent_item' => sprintf(
                /* translators: %s - taxonomy singular name */
                _x('Parent %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'parent_item_colon' => sprintf(
                /* translators: %s - taxonomy singular name */
                _x('Parent %s:', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'edit_item' => sprintf(
                 /* translators: %s - taxonomy singular name */
                _x('Edit %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'view_item' => sprintf(
                 /* translators: %s - taxonomy singular name */
                _x('View %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'update_item' => sprintf(
                 /* translators: %s - taxonomy singular name */
                _x('Update %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'add_new_item' => sprintf(
                 /* translators: %s - taxonomy singular name */
                _x('Add New %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'new_item_name' => sprintf(
                 /* translators: %s - taxonomy singular name */
                _x('New %s Name', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'separate_items_with_commas' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x(
                    'Separate %s with commas',
                    'backoffice: taxonomy label',
                    'fau-degree-program-common'
                ),
                $plural
            ),
            'add_or_remove_items' => sprintf(
                 /* translators: %s - taxonomy plural name */
                _x('Add or remove %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'choose_from_most_used' => sprintf(
                 /* translators: %s - taxonomy plural name */
                _x(
                    'Choose from the most used %s',
                    'backoffice: taxonomy label',
                    'fau-degree-program-common'
                ),
                $plural
            ),
            'not_found' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x('No %s found.', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'no_terms' => sprintf(
                 /* translators: %s - taxonomy plural name */
                _x('No %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'filter_by_item' => sprintf(
                /* translators: %s - taxonomy singular name */
                _x('Filter by %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'items_list_navigation' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x(
                    '%s list navigation',
                    'backoffice: taxonomy label',
                    'fau-degree-program-common'
                ),
                $plural
            ),
            'items_list' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x('%s list', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'back_to_items' => sprintf(
                /* translators: %s - taxonomy plural name */
                _x('&larr; Go to %s', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $plural
            ),
            'item_link' => sprintf(
               /* translators: %s - taxonomy singular name */
                _x('%s Link', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
            'item_link_description' => sprintf(
                 /* translators: %s - taxonomy singular name */
                _x('A link to a %s.', 'backoffice: taxonomy label', 'fau-degree-program-common'),
                $singular
            ),
        ];
    }

    final public function showAdminColumn(): static
    {
        return $this->merge([
            'show_admin_column' => true,
        ]);
    }
}
