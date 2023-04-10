<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\Violation;
use Fau\DegreeProgram\Common\Domain\Violations;
use Fau\DegreeProgram\Common\Tests\FixtureDegreeProgramDataProviderTrait;
use Fau\DegreeProgram\Common\Tests\Sanitizer\StubSanitizer;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;
use Fau\DegreeProgram\Common\Tests\Validator\StubDataValidator;
use InvalidArgumentException;
use RuntimeException;

class DegreeProgramTest extends UnitTestCase
{
    use FixtureDegreeProgramDataProviderTrait;

    public function testUpdateWithWrongId(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid entity id.');
        $sut = $this->createEmptyDegreeProgram(25);
        $data = $this->fixtureData();
        $wrongId = 12312;
        $data['id'] = $wrongId;

        $sut->update(
            $data,
            new StubDataValidator(Violations::new()),
            new StubSanitizer(),
        );
    }

    public function testUpdateValidationFailed(): void
    {
        $violations = Violations::new(Violation::new('title', 'Empty title', 'empty_title'));
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Invalid degree program data. Violations: %s.',
            implode('|', array_keys($violations->getArrayCopy()))
        ));
        $sut = $this->createEmptyDegreeProgram(25);
        $data = $this->fixtureData();

        $sut->update(
            $data,
            new StubDataValidator($violations),
            new StubSanitizer(),
        );
    }

    public function testUpdateSuccessfully(): void
    {
        $sut = $this->createEmptyDegreeProgram(25);
        $data = $this->fixtureData();
        $sut->update(
            $data,
            new StubDataValidator(Violations::new()),
            new StubSanitizer('[Was sanitized]'),
        );
        $result = $sut->asArray();

        $this->assertSame(
            25,
            $result['id']->asInt()
        );
        $this->assertSame(
            9,
            $result['featured_image']->id()
        );
        $this->assertSame(
            14,
            $result['teaser_image']->id()
        );
        $this->assertSame(
            'Master of Art FAU EN',
            $result['title']->inEnglish()
        );
        $this->assertSame(
            'Subtitle',
            $result['subtitle']->inGerman()
        );
        $this->assertSame(
            'Winter EN',
            $result['start']->asArrayOfStrings('en')[1]
        );
        $this->assertSame(
            '<p>Less</p>',
            $result['number_of_students']->description()
        );
        $this->assertSame(
            'German Formal',
            $result['teaching_language']->inGerman()
        );
        $this->assertSame(
            'DE',
            $result['attributes']->asArrayOfStrings('de')[0]
        );
        $this->assertSame(
            'One Degree',
            $result['degree']->name()->inGerman()
        );
        $this->assertSame(
            'Link Faculty Math EN',
            $result['faculty']->asArray()[0]['link_text']['en']
        );
        $this->assertSame(
            'Study location',
            $result['location']->asArrayOfStrings('de')[0]
        );
        $this->assertSame(
            'Subject Bio EN',
            $result['subject_groups']->asArrayOfStrings('en')[0]
        );
        $this->assertSame(
            [
                "https://www.youtube.com/",
                "https://vimeo.com/",
            ],
            $result['videos']->getArrayCopy()
        );
        $this->assertSame(
            'Meta description.',
            $result['meta_description']->inGerman()
        );
        // The title is missing in the fixture but added in the entity constructor as the default value.
        $this->assertSame(
            'Aufbau und Struktur',
            $result['content']->structure()->title()->inGerman()
        );
        $this->assertSame(
            '[Was sanitized]Structure description.',
            $result['content']->structure()->description()->inGerman()
        );
        $this->assertSame(
            'Admission Bachelor',
            $result['admission_requirements']->bachelorOrTeachingDegree()->name()->inGerman()
        );
        $this->assertSame(
            '[Was sanitized]Master requirements.',
            $result['content_related_master_requirements']->inGerman()
        );
        $this->assertSame(
            '01.12.',
            $result['application_deadline_winter_semester']
        );
        $this->assertSame(
            '01.07.',
            $result['application_deadline_summer_semester']
        );
        $this->assertSame(
            '[Was sanitized]Notes EN.',
            $result['details_and_notes']->inEnglish()
        );
        $this->assertSame(
            '[Was sanitized]C1',
            $result['language_skills']->inEnglish()
        );
        $this->assertSame(
            '[Was sanitized]Excellent',
            $result['language_skills_humanities_faculty']
        );
        $this->assertSame(
            'https://fau.localhost/german-language-skills-international-students-en',
            $result['german_language_skills_for_international_students']->linkUrl()->inEnglish()
        );
        $this->assertSame(
            'Link to Start of Semester EN',
            $result['start_of_semester']->linkText()->inEnglish()
        );
        $this->assertSame(
            'Link text Semester dates EN',
            $result['semester_dates']->linkText()->inEnglish()
        );
        $this->assertSame(
            'Link Examinations Office EN',
            $result['examinations_office']->linkText()->inEnglish()
        );
        $this->assertSame(
            'https://fau.localhost/examinations-regulations',
            $result['examination_regulations']
        );
        $this->assertSame(
            'Module handbook value',
            $result['module_handbook']
        );
        $this->assertSame(
            'https://fau.localhost/science-en',
            $result['department']->inEnglish(),
        );
        $this->assertSame(
            'Link Student Advice and Career Service EN',
            $result['student_advice']->linkText()->inEnglish()
        );
        $this->assertSame(
            'Link to Advice EN',
            $result['subject_specific_advice']->linkText()->inEnglish()
        );
        $this->assertSame(
            'Link Counseling and Service Centers at FAU EN',
            $result['service_centers']->linkText()->inEnglish()
        );
        $this->assertSame(
            'John Doe',
            $result['student_representatives']
        );
        $this->assertSame(
            'https://fau.localhost/semester-fee',
            $result['semester_fee']->linkUrl()->inGerman()
        );
        $this->assertSame('EUR 1000', $result['degree_program_fees']->inEnglish());
        $this->assertSame(
            'Opportunities for spending time abroad',
            $result['abroad_opportunities']->name()->inGerman()
        );
        $this->assertSame(
            'Keyword 1 EN',
            $result['keywords']->asArrayOfStrings('en')[0]
        );
        $this->assertSame(
            'https://fau.localhost/biology',
            $result['area_of_study']->asArray()[0]['link_url']['de']
        );
        $this->assertSame(
            [26, 28],
            $result['combinations']->asArray()
        );
        $this->assertSame(
            [26],
            $result['limited_combinations']->asArray()
        );
        $this->assertSame(
            [26, 28],
            $result['combinations_changeset']->added()
        );
        $this->assertSame(
            [],
            $result['combinations_changeset']->removed()
        );
        $this->assertSame(
            [26],
            $result['limited_combinations_changeset']->added()
        );
        $this->assertSame(
            [],
            $result['limited_combinations_changeset']->removed()
        );
        $this->assertSame(
            'Notes for International Applicants',
            $result['notes_for_international_applicants']->name()->inGerman()
        );
    }
}
