<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Validator;

use Fau\DegreeProgram\Common\Infrastructure\Validator\JsonSchemaDegreeProgramDataValidator;
use Fau\DegreeProgram\Common\Tests\FixtureDegreeProgramDataProviderTrait;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;

class JsonSchemaDegreeProgramDataValidatorTest extends WpDbLessTestCase
{
    use FixtureDegreeProgramDataProviderTrait;

    /**
     * @dataProvider fixtureDataProvider
     */
    public function testSuccessfulValidation(array $fixtureData): void
    {
        $sut = new JsonSchemaDegreeProgramDataValidator();
        $violations = $sut->validate($fixtureData);
        $this->assertCount(0, $violations->getArrayCopy());
    }

    /**
     * @dataProvider fixtureDataProvider
     */
    public function testViolations(array $fixtureData): void
    {
        unset($fixtureData['content']);
        $sut = new JsonSchemaDegreeProgramDataValidator();
        $violations = $sut->validate($fixtureData);
        $this->assertCount(1, $violations->getArrayCopy());
        $this->assertSame(
            'content is a required property of degree_program.',
            $violations[0]
        );
    }
}
