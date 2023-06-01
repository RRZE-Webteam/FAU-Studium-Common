<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use WP_Term;

final class StickyDegreeProgramRepository
{
    public function toggleSticky(int $postId, ?WP_Term $term): bool
    {
        $isSticky = $this->isSticky($postId, $term);

        return update_post_meta($postId, self::stickyKey($term), !$isSticky) !== false;
    }

    public function isSticky(int $postId, ?WP_Term $term): bool
    {
        return (bool) get_post_meta($postId, self::stickyKey($term), true);
    }

    public static function stickyKey(?WP_Term $term): string
    {
        $pieces = ['_sticky'];
        if ($term instanceof WP_Term) {
            $pieces[] = $term->taxonomy;
            $pieces[] = $term->term_id;
        }

        return implode('_', $pieces);
    }
}
