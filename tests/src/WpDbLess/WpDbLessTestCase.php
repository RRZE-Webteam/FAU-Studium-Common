<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\WpDbLess;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestResult;
use PHPUnit\Util\Test as TestUtil;
use Throwable;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @psalm-suppress UnresolvableInclude
 */
class WpDbLessTestCase extends TestCase
{
    private ?Throwable $throwable = null;
    private WpHook $wpHook;
    private WpOption $wpOption;

    protected function setUp(): void
    {
        $this->wpHook = new WpHook();
        $this->wpOption = new WpOption([
            'siteurl' => 'https://wp-db-less',
        ]);

        if (!defined('ABSPATH')) {
            define('ABSPATH', (string) getcwd() . '/vendor/johnpbloch/wordpress-core/');
        }

        require_once ABSPATH . 'wp-includes/class-wpdb.php';
        $GLOBALS['wpdb'] = new WpDbStub();

        $this->wpHook->addHook(
            'alloptions',
            [$this->wpOption, 'options']
        );
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['wpdb'], $GLOBALS['wp_filter']);
    }

    public function run(TestResult $result = null): TestResult
    {
        $this->runTestInSeparateProcess = true;

        return parent::run($result);
    }

    protected function runTest(): void
    {
        if ($this->throwable) {
            parent::runTest();
            return;
        }

        $hook = $this->detectHook() ?? 'muplugins_loaded';

        // phpcs:disable Inpsyde.CodeQuality.StaticClosure.PossiblyStaticClosure
        $this->runWithThrowableCatching($hook, function () {
            $this->setUpAfterWordPressLoaded();
            parent::runTest();
        });

        $this->loadWordPress();
    }

    private function loadWordPress(): void
    {
        // phpcs:disable Inpsyde.CodeQuality.VariablesName.SnakeCaseVar
        $table_prefix = 'wp_db_less_';
        require_once ABSPATH . 'wp-settings.php';
    }

    /**
     * Exception throwing inside WordPress hook
     * is not cached by \PHPUnit\Framework\TestCase::runTest
     * so we provide a workaround to catch it and rethrow
     * with fake assertThrowable test method.
     */
    private function runWithThrowableCatching(
        string $hook,
        callable $callback,
    ): void {

        $exceptionAwareCallback = function () use ($callback): void {
            try {
                $callback();
            } catch (Throwable $throwable) {
                $this->throwable = $throwable;
                /** @psalm-suppress InternalMethod */
                $this->setName('assertThrowable');
                $this->runTest();
            }
        };

        $this->wpHook->addHook($hook, $exceptionAwareCallback, 0);
    }

    /**
     * @internal
     */
    protected function assertThrowable(): void
    {
        if ($this->throwable instanceof Throwable) {
            throw $this->throwable;
        }
    }

    /**
     * @psalm-suppress InternalMethod
     * @psalm-suppress InternalClass
     */
    private function detectHook(): ?string
    {
        $annotations = TestUtil::parseTestMethodAnnotations(
            static::class,
            $this->getName()
        );

        if (!isset($annotations['method']['wp-hook'][0])) {
            return null;
        }

        return (string) $annotations['method']['wp-hook'][0];
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.NoAccessors.NoSetter
     */
    public function setUpAfterWordPressLoaded(): void
    {
    }
}
