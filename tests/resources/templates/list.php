<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Common\Tests\Dto\ListDto;

/**
 * @var array $data
 * @var ListDto $list
 * @var Renderer $renderer
 */

['list' => $list] = $data; ?>

<ul>
    <?php
    foreach ($list->listItems() as $item) {
        echo $renderer->render('partials/item.php', ['item' => $item]);
    } ?>
</ul>
