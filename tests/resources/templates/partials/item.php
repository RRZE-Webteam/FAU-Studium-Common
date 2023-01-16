<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Common\Tests\Dto\ListItemDto;

/**
 * @var array $data
 * @var ListItemDto $item
 * @var Renderer $renderer
 */

['item' => $item] = $data; ?>

<li><?php echo $renderer->render(
    'partials/link.php',
    ['nested_array' => ['url' => $item->url(), 'title' => $item->title()]]
) ?>
</li>

<?php
