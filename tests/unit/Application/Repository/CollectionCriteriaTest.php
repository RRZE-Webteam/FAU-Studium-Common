<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Application\Repository;

use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use PHPUnit\Framework\TestCase;

class CollectionCriteriaTest extends TestCase
{
    public function testPagination(): void
    {
        $sut = CollectionCriteria::new();

        $this->assertSame(1, $sut->page());
        $this->assertSame(10, $sut->perPage());

        $sut = $sut->withoutPagination();
        $this->assertSame(1, $sut->page());
        $this->assertSame(-1, $sut->perPage());

        $sut = $sut->withPage(2)->withPerPage(20);
        $this->assertSame(2, $sut->page());
        $this->assertSame(20, $sut->perPage());

        $sut = $sut->toNextPage();
        $this->assertSame(3, $sut->page());
        $this->assertSame(20, $sut->perPage());
    }

    public function testLanguageCode(): void
    {
        $sut = CollectionCriteria::new()->withLanguage('en');
        $this->assertSame('en', $sut->languageCode());
    }

    public function testOrdering(): void
    {
        $sut = CollectionCriteria::new();
        $this->assertSame([], $sut->args()['order_by']);

        $sut = $sut->withOrderBy(['title' => 'asc']);
        $this->assertSame(['title' => 'asc'], $sut->args()['order_by']);

        $sut = $sut->withOrderBy(['unknown' => null, 'title' => 'desc']);
        $this->assertSame(['title' => 'desc'], $sut->args()['order_by']);
    }
}
