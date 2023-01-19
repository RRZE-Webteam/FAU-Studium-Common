<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Domain;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\ContentItem;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\DegreeProgramIds;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use Fau\DegreeProgram\Common\Tests\UnitTestCase;
use Fau\DegreeProgram\Common\Tests\Validator\StubDataValidator;
use InvalidArgumentException;
use RuntimeException;

class DegreeProgramTest extends UnitTestCase
{
    public function testUpdateWithWrongId(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid entity id.');
        $sut = $this->createDegreeProgram();
        $data = $this->fixtureData();
        $wrongId = 12312;
        $data['id'] = $wrongId;

        $sut->update($data, new StubDataValidator(ArrayOfStrings::new()));
    }

    public function testUpdateValidationFailed(): void
    {
        $violations = ArrayOfStrings::new('Empty title');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Invalid degree program data. Violations: %s.',
            implode('|', $violations->getArrayCopy())
        ));
        $sut = $this->createDegreeProgram();
        $data = $this->fixtureData();

        $sut->update($data, new StubDataValidator($violations));
    }

    public function testUpdateSuccessfully(): void
    {
        $sut = $this->createDegreeProgram();
        $data = $this->fixtureData();
        $sut->update($data, new StubDataValidator(ArrayOfStrings::new()));
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
            $result['faculty']->linkText()->inEnglish()
        );
        $this->assertSame(
            'Study location',
            $result['location']->inGerman()
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
            'Structure description.',
            $result['content']->structure()->description()->inGerman()
        );
        $this->assertSame(
            'Admission Bachelor',
            $result['admission_requirements']->bachelorOrTeachingDegree()->name()->inGerman()
        );
        $this->assertSame(
            'Master requirements.',
            $result['content_related_master_requirements']->inGerman()
        );
        $this->assertSame(
            '1/12',
            $result['application_deadline_winter_semester']
        );
        $this->assertSame(
            '1/07',
            $result['application_deadline_summer_semester']
        );
        $this->assertSame(
            'Notes EN.',
            $result['details_and_notes']->inEnglish()
        );
        $this->assertSame(
            'C1',
            $result['language_skills']->inEnglish()
        );
        $this->assertSame(
            'Excellent',
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
            'Link regulations EN',
            $result['examination_regulations']->linkText()->inEnglish()
        );
        $this->assertSame(
            'Module handbook value',
            $result['module_handbook']
        );
        $this->assertSame(
            'Link Department EN',
            $result['department']->linkText()->inEnglish()
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
    }

    private function createDegreeProgram(): DegreeProgram
    {
        return new DegreeProgram(
            id: DegreeProgramId::fromInt(25),
            featuredImage: Image::empty(),
            teaserImage: Image::empty(),
            title: MultilingualString::empty(),
            subtitle: MultilingualString::empty(),
            standardDuration: 0,
            feeRequired: false,
            start: MultilingualList::new(),
            numberOfStudents: NumberOfStudents::empty(),
            teachingLanguage: MultilingualString::empty(),
            attributes: MultilingualList::new(),
            degree: Degree::empty(),
            faculty: MultilingualLink::empty(),
            location: MultilingualString::empty(),
            subjectGroups: MultilingualList::new(),
            videos: ArrayOfStrings::new(),
            metaDescription: MultilingualString::empty(),
            content: Content::new(
                about: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
                structure: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
                specializations: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
                qualitiesAndSkills: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
                whyShouldStudy: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
                careerProspects: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
                specialFeatures: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
                testimonials: ContentItem::new(MultilingualString::empty(), MultilingualString::empty()),
            ),
            admissionRequirements: AdmissionRequirements::new(
                bachelorOrTeachingDegree: MultilingualLink::empty(),
                teachingDegreeHigherSemester: MultilingualLink::empty(),
                master: MultilingualLink::empty(),
            ),
            contentRelatedMasterRequirements: MultilingualString::empty(),
            applicationDeadlineWinterSemester: '',
            applicationDeadlineSummerSemester: '',
            detailsAndNotes: MultilingualString::empty(),
            languageSkills: MultilingualString::empty(),
            languageSkillsHumanitiesFaculty: '',
            germanLanguageSkillsForInternationalStudents: MultilingualLink::empty(),
            startOfSemester: MultilingualLink::empty(),
            semesterDates: MultilingualLink::empty(),
            examinationsOffice: MultilingualLink::empty(),
            examinationRegulations: MultilingualLink::empty(),
            moduleHandbook: '',
            url: MultilingualString::empty(),
            department: MultilingualLink::empty(),
            studentAdvice: MultilingualLink::empty(),
            subjectSpecificAdvice: MultilingualLink::empty(),
            serviceCenters: MultilingualLink::empty(),
            studentRepresentatives: '',
            semesterFee: MultilingualLink::empty(),
            degreeProgramFees: MultilingualString::empty(),
            abroadOpportunities: MultilingualLink::empty(),
            keywords: MultilingualList::new(),
            areaOfStudy: MultilingualLinks::new(),
            combinations: DegreeProgramIds::new(),
            limitedCombinations: DegreeProgramIds::new(),
        );
    }

    private function fixtureData(): array
    {
        static $data;
        if (isset($data)) {
            return $data;
        }

        $data = json_decode(
            file_get_contents(RESOURCES_DIR . '/fixtures/degree_program.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return $data;
    }
}
