<?php

declare(strict_types=1);

//phpcs:disable Inpsyde.CodeQuality.NoTopLevelDefine.Found

define('TESTS_DIR', __DIR__);
define('VENDOR_DIR', dirname(__DIR__) . '/vendor');
define('TEMPLATES_DIR', TESTS_DIR . '/resources/templates');
#
defined('ABSPATH') or define('ABSPATH', VENDOR_DIR . '/johnpbloch/wordpress-core');
defined('WPINC') or define('WPINC', '/wp-includes');
