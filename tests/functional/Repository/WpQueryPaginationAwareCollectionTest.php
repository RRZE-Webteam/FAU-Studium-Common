<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Repository;

use Fau\DegreeProgram\Common\Infrastructure\Repository\WpQueryPaginationAwareCollection;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;
use WP_Query;

final class WpQueryPaginationAwareCollectionTest extends WpDbLessTestCase
{
    public function testMiddlePage(): void
    {
        $query = new WP_Query();
        $query->init();
        $query->posts = [1, 2, 3, 4, 5];
        $query->found_posts = 51;
        $query->set('paged', 2);
        $query->set('posts_per_page', 5);

        $sut = new WpQueryPaginationAwareCollection($query);
        $this->assertSame(2, $sut->currentPage());
        $this->assertSame(3, $sut->nextPage());
        $this->assertSame(1, $sut->previousPage());
        $this->assertSame(11, $sut->maxPages());
        $this->assertSame(51, $sut->totalItems());
    }

    public function testFirstPage(): void
    {
        $query = new WP_Query();
        $query->init();
        $query->posts = [1, 2, 3, 4, 5];
        $query->found_posts = 51;
        $query->set('posts_per_page', 5);

        $sut = new WpQueryPaginationAwareCollection($query);
        $this->assertSame(1, $sut->currentPage());
        $this->assertSame(2, $sut->nextPage());
        $this->assertNull($sut->previousPage());
        $this->assertSame(11, $sut->maxPages());
        $this->assertSame(51, $sut->totalItems());
    }

    public function testLastPage(): void
    {
        $query = new WP_Query();
        $query->init();
        $query->posts = [1, 2, 3, 4, 5];
        $query->found_posts = 51;
        $query->set('paged', 11);
        $query->set('posts_per_page', 5);

        $sut = new WpQueryPaginationAwareCollection($query);
        $this->assertSame(11, $sut->currentPage());
        $this->assertNull($sut->nextPage());
        $this->assertSame(10, $sut->previousPage());
        $this->assertSame(11, $sut->maxPages());
        $this->assertSame(51, $sut->totalItems());
    }

    public function testInvalid(): void
    {
        $query = new WP_Query();
        $query->init();
        $query->posts = [];
        $query->found_posts = 51;
        $query->set('paged', 15);
        $query->set('posts_per_page', 5);

        $sut = new WpQueryPaginationAwareCollection($query);
        $this->assertSame(15, $sut->currentPage());
        $this->assertNull($sut->nextPage());
        $this->assertSame(11, $sut->previousPage());
        $this->assertSame(11, $sut->maxPages());
        $this->assertSame(51, $sut->totalItems());
    }
}
