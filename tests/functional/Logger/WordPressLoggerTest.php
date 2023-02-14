<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Logger;

use Exception;
use Fau\DegreeProgram\Common\Infrastructure\Logger\WordPressLogger;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;

/**
 * phpcs:disable WordPress.PHP.IniSet.Risky
 */
final class WordPressLoggerTest extends WpDbLessTestCase
{
    private const PACKAGE = 'my_package';
    private $tmpStream;
    private string|false $oldErrorLogValue;

    public function setUp(): void
    {
        $this->tmpStream = tmpfile();
        $this->oldErrorLogValue = ini_set(
            'error_log',
            stream_get_meta_data($this->tmpStream)['uri']
        );
        define('WP_DEBUG_LOG', true);
        parent::setUp();

        $this->wpOption->addOption('home', 'https://fau.de');
    }

    public function tearDown(): void
    {
        ini_set('error_log', $this->oldErrorLogValue);
        parent::tearDown();
    }

    public function testWordPressHook(): void
    {
        add_action(self::PACKAGE . '.error', static function (array $context) {
            self::assertSame('Error happens!', $context['message']);
        });

        $sut = WordPressLogger::new(self::PACKAGE);
        $sut->error('Error happens!');
        $sut->info('Some info.');
        self::assertSame(1, did_action(self::PACKAGE . '.error'));
        self::assertSame(1, did_action(self::PACKAGE . '.info'));
    }

    public function testLogEntry(): void
    {
        $sut = WordPressLogger::new(self::PACKAGE);
        $sut->error('Error happens!');
        self::assertStringEndsWith(
            "[ERROR] [my_package]: Error happens!\n{\"site_url\":\"https:\/\/fau.de\"}\n",
            stream_get_contents($this->tmpStream)
        );
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
     */
    public function testContext(): void
    {
        add_action(self::PACKAGE . '.error', static function (array $context) {
            self::assertSame('here', $context['where']);
        });

        $sut = WordPressLogger::new(self::PACKAGE);
        $sut->error('Error happens!', ['where' => 'here']);
        self::assertStringEndsWith(
            "[ERROR] [my_package]: Error happens!\n{\"where\":\"here\",\"site_url\":\"https:\/\/fau.de\"}\n",
            stream_get_contents($this->tmpStream)
        );
    }

    public function testException(): void
    {
        $exception = new Exception('Error!');

        add_action(self::PACKAGE . '.error', static function (array $context) {
            self::assertSame('Error happens!', $context['message']);
            self::assertInstanceOf(Exception::class, $context['exception']);
            self::assertSame('Error!', $context['exception']->getMessage());
        });

        $sut = WordPressLogger::new(self::PACKAGE);
        $sut->error('Error happens!', ['exception' => $exception]);
        $entries = explode("\n", stream_get_contents($this->tmpStream));
        self::assertStringEndsWith(
            "[ERROR] [my_package]: Error happens!",
            $entries[0]
        );
        self::assertStringEndsWith(
            "Error!",
            $entries[1]
        );
        self::assertStringEndsWith(
            "Fau\DegreeProgram\Common\Tests\Logger\WordPressLoggerTest->testException()",
            $entries[2]
        );
    }
}
