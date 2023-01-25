<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Content;

use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\DegreeTaxonomy;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;

final class TaxonomyTest extends UnitTestCase
{
    public function testTaxonomyArgGeneration(): void
    {
        $expectedLabels = [
            'name' => 'Degrees',
            'singular_name' => 'Degree',
            'search_items' => 'Search Degrees',
            'popular_items' => 'Popular Degrees',
            'all_items' => 'All Degrees',
            'parent_item' => 'Parent Degree',
            'parent_item_colon' => 'Parent Degree:',
            'edit_item' => 'Edit Degree',
            'view_item' => 'View Degree',
            'update_item' => 'Update Degree',
            'add_new_item' => 'Add New Degree',
            'new_item_name' => 'New Degree Name',
            'separate_items_with_commas' => 'Separate Degrees with commas',
            'add_or_remove_items' => 'Add or remove Degrees',
            'choose_from_most_used' => 'Choose from the most used Degrees',
            'not_found' => 'No Degrees found.',
            'no_terms' => 'No Degrees',
            'filter_by_item' => 'Filter by Degree',
            'items_list_navigation' => 'Degrees list navigation',
            'items_list' => 'Degrees list',
            'back_to_items' => '&larr; Go to Degrees',
            'item_link' => 'Degree Link',
            'item_link_description' => 'A link to a Degree.',
        ];

        $this->assertSame(
            [
                'label' => 'Degrees',
                'labels' => $expectedLabels,
                'hierarchical' => true,
                'rest_base' => 'degree',
                'public' => true,
                'show_in_rest' => true,
                'meta_box_cb' => false,
            ],
            DegreeTaxonomy::public()->args()
        );

        $this->assertSame(
            [
                'label' => 'Degrees',
                'labels' => $expectedLabels,
                'hierarchical' => true,
                'rest_base' => 'degree',
                'public' => false,
                'show_in_rest' => false,
            ],
            DegreeTaxonomy::hidden()->args()
        );
    }

    public function testShowAdminColumn(): void
    {
        $args = DegreeTaxonomy::public()
            ->showAdminColumn()
            ->args();
        $this->assertTrue($args['show_admin_column']);
    }

    public function testMerging(): void
    {
        $args = DegreeTaxonomy::public()
            ->merge([
                'public' => false,
                'show_in_quick_edit' => true,
            ])
            ->args();

        $this->assertFalse($args['public']);
        $this->assertTrue($args['show_in_quick_edit']);
    }
}
