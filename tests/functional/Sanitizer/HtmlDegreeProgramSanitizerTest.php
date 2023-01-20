<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Sanitizer;

use Fau\DegreeProgram\Common\Infrastructure\Sanitizer\HtmlDegreeProgramSanitizer;
use Fau\DegreeProgram\Common\Tests\AsserHtmlTrait;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;

class HtmlDegreeProgramSanitizerTest extends WpDbLessTestCase
{
    use AsserHtmlTrait;

    /**
     * @dataProvider serializedBlocksDataProvider
     */
    public function testSanitizeContentField(string $input, string $output): void
    {
        add_shortcode('fau-video', static fn() => '');
        add_shortcode('wrong_shortcode', static fn() => '');

        $sut = new HtmlDegreeProgramSanitizer();

        $this->assertHtmlEqual($output, $sut->sanitizeContentField($input));
    }

    public function serializedBlocksDataProvider(): iterable
    {
        $input = (string) file_get_contents(
            RESOURCES_DIR . '/fixtures/content_field_html_input.html'
        );
        $output = (string) file_get_contents(
            RESOURCES_DIR . '/fixtures/content_field_html_output.html'
        );

        yield 'basic_data' => [$input, $output];
    }
}
