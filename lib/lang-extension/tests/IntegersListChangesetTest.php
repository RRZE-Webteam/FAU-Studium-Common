<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\LanguageExtension\Tests;

use Fau\DegreeProgram\Common\LanguageExtension\IntegersListChangeset;
use PHPUnit\Framework\TestCase;

final class IntegersListChangesetTest extends TestCase
{
    public function testNew(): void
    {
        $changeset = IntegersListChangeset::new(
            [1,2,3],
        );

        $this->assertSame([], $changeset->added());
        $this->assertSame([], $changeset->removed());
    }

    public function testIntegers(): void
    {
        $changeset = IntegersListChangeset::new(
            [1,2,3],
        )->applyChanges([2,4,5]);

        $this->assertSame([4,5], $changeset->added());
        $this->assertSame([1,3], $changeset->removed());
    }

    public function testUnchanged(): void
    {
        $changeset = IntegersListChangeset::new(
            [1,2,3],
        )->applyChanges([1,2,3]);

        $this->assertSame([], $changeset->added());
        $this->assertSame([], $changeset->removed());
    }

    public function testEmptiness(): void
    {
        $changeset = IntegersListChangeset::new(
            [1,2,3],
        )->applyChanges([]);

        $this->assertSame([], $changeset->added());
        $this->assertSame([1,2,3], $changeset->removed());
    }
}
