<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\WpDbLess;

/**
 * phpcs:disable
 */
class WpDbStub extends \wpdb
{
    public function __construct()
    {
    }

    public function get_results($query = null, $output = OBJECT)
    {
        return [];
    }

    public function _real_escape($string)
    {
        return $string;
    }
}
