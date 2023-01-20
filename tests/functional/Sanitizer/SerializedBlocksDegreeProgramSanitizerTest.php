<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Sanitizer;

use Fau\DegreeProgram\Common\Infrastructure\Sanitizer\SerializedBlocksDegreeProgramSanitizer;
use Fau\DegreeProgram\Common\Tests\AsserHtmlTrait;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;

class SerializedBlocksDegreeProgramSanitizerTest extends WpDbLessTestCase
{
    use AsserHtmlTrait;

    /**
     * @dataProvider serializedBlocksDataProvider
     */
    public function testSanitizeContentField(string $input, string $output): void
    {
        $sut = new SerializedBlocksDegreeProgramSanitizer();
        $this->assertHtmlEqual($output, $sut->sanitizeContentField($input));
    }

    public function serializedBlocksDataProvider(): iterable
    {
        $input = (string) file_get_contents(
            RESOURCES_DIR . '/fixtures/content_field_serialized_blocks_input.html'
        );
        $output = (string) file_get_contents(
            RESOURCES_DIR . '/fixtures/content_field_serialized_blocks_output.html'
        );

        yield 'basic_data' => [$input, $output];
    }
}
