<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Infrastructure\Validator;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Repository\FacultyRepository;
use Fau\DegreeProgram\Common\Infrastructure\Validator\ConditionalFieldsValidator;
use Fau\DegreeProgram\Common\Tests\FixtureDegreeProgramDataProviderTrait;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;

class ConditionalFieldsValidatorTest extends WpDbLessTestCase
{
    use FixtureDegreeProgramDataProviderTrait;

    public function testValidate(): void
    {
        $data = $this->fixtureData();
        $sut = new ConditionalFieldsValidator(
            new FacultyRepository(),
        );
        $result = $sut->validate($data);
        $this->assertSame($result->count(), 0);
    }

    public function testEmptyDegreeProgramFees(): void
    {
        $data = $this->fixtureData();
        $data[DegreeProgram::DEGREE_PROGRAM_FEES] = MultilingualString::empty()->asArray();
        $sut = new ConditionalFieldsValidator(
            new FacultyRepository(),
        );
        $violations = $sut->validate($data);
        $this->assertSame($violations->count(), 2);
        $this->assertSame([
            'degree_program_fees.de' => [
                'path' => 'degree_program_fees.de',
                'errorMessage' => 'This field can not be empty.',
                'errorCode' => 'rest_too_short',
            ],
            'degree_program_fees.en' => [
                'path' => 'degree_program_fees.en',
                'errorMessage' => 'This field can not be empty.',
                'errorCode' => 'rest_too_short',
            ],
        ], $violations->asArray());
    }

    public function testEmptyMasterAdmissionRequirement(): void
    {
        $data = $this->fixtureData();
        $data[DegreeProgram::DEGREE][Degree::ABBREVIATION][MultilingualString::DE] = 'MA';
        $data[DegreeProgram::ADMISSION_REQUIREMENTS][AdmissionRequirements::MASTER] = AdmissionRequirement::empty()->asArray();
        $sut = new ConditionalFieldsValidator(
            new FacultyRepository(),
        );
        $violations = $sut->validate($data);
        $this->assertSame($violations->count(), 1);
        $this->assertSame([
            'admission_requirements.master' => [
                'path' => 'admission_requirements.master',
                'errorMessage' => 'This field can not be empty.',
                'errorCode' => 'rest_too_short',
            ],
        ], $violations->asArray());
    }

    public function testSkipValidationOnAdditionalDegree(): void
    {
        $data = $this->fixtureData();
        $data[DegreeProgram::DEGREE][Degree::NAME][MultilingualString::DE] = 'Weiteres';
        $data[DegreeProgram::ADMISSION_REQUIREMENTS][AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE] = AdmissionRequirement::empty()->asArray();
        $sut = new ConditionalFieldsValidator(
            new FacultyRepository(),
        );
        $violations = $sut->validate($data);
        $this->assertSame($violations->count(), 0);
    }
}
