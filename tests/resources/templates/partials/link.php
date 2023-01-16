<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Tests\Dto\ListItemDto;

/**
 * @var array $data
 * @var ListItemDto $item
 */

['nested_array' => ['url' => $url, 'title' => $title]] = $data; ?>

<a href="<?= $url ?>"><?= $title ?></a>
