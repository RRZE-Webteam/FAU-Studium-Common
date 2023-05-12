<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;

final class OrderRepository
{
    private const MAX_ORDER_NUMBER = 9999;
    /**
     * @param array<int> $postIds
     */
    public function updateOrderByTerm(int $start, array $postIds, string $taxonomy, int $term): void
    {
        $key = self::orderByTermKey($taxonomy, $term);
        foreach ($postIds as $id) {
            update_post_meta($id, $key, $start++);
        }

        /** @var array<int> $notSortedPostIds */
        $notSortedPostIds = get_posts([
           'post_type' => DegreeProgramPostType::KEY,
           'posts_per_page' => -1,
           'post_status' => 'any',
           'meta_query' => [
               [
                   'key' => $key,
                   'compare' => 'NOT EXISTS',
               ],
           ],
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'terms' => [$term],
                ],
            ],
            'fields' => 'ids',
        ]);

        foreach ($notSortedPostIds as $id) {
            update_post_meta($id, $key, self::MAX_ORDER_NUMBER);
        }
    }

    public function updateMenuOrder(int $start, array $postIds): void
    {
        global $wpdb;

        foreach ($postIds as $id) {
            $wpdb->update(
                $wpdb->posts,
                ['menu_order' => $start++],
                ['ID' => $id]
            );
        }

        $wpdb->update(
            $wpdb->posts,
            ['menu_order' => self::MAX_ORDER_NUMBER],
            [
                'menu_order' => 0,
                'post_type' => DegreeProgramPostType::KEY,
            ]
        );
    }

    public static function orderByTermKey(string $taxonomy, int $term): string
    {
        return sprintf('_order_%s_%d', $taxonomy, $term);
    }
}
