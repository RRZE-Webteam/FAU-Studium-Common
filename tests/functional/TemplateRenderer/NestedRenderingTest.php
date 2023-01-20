<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\TemplateRenderer;

use Fau\DegreeProgram\Common\Tests\Dto\ListDto;
use Fau\DegreeProgram\Common\Tests\Dto\ListItemDto;
use Fau\DegreeProgram\Common\Tests\RendererTestCase;

final class NestedRenderingTest extends RendererTestCase
{
    public function testNested(): void
    {
        $this->assertHtmlEqual(
            '<ul><li><a href="https://google.com">Google</a></li></ul>',
            $this->sut->render('list.php', ['list' => $this->prepareList()])
        );
    }

    private function prepareList(): ListDto
    {
        $listItem = new ListItemDto('https://google.com', 'Google');
        return new ListDto('Search Engines', $listItem);
    }
}
