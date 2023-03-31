<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
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
use Generator;

trait FixtureDegreeProgramDataProviderTrait
{
    public function fixtureData(): array
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

    public function fixtureDataProvider(): Generator
    {
        yield 'basic_data' => [$this->fixtureData()];
    }

    public function createEmptyDegreeProgram(int $id): DegreeProgram
    {
        return new DegreeProgram(
            id: DegreeProgramId::fromInt($id),
            slug: MultilingualString::empty(),
            featuredImage: Image::empty(),
            teaserImage: Image::empty(),
            title: MultilingualString::empty(),
            subtitle: MultilingualString::empty(),
            standardDuration: '',
            feeRequired: false,
            start: MultilingualList::new(),
            numberOfStudents: NumberOfStudents::empty(),
            teachingLanguage: MultilingualString::empty(),
            attributes: MultilingualList::new(),
            degree: Degree::empty(),
            faculty: MultilingualLinks::new(),
            location: MultilingualList::new(),
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
                bachelorOrTeachingDegree: AdmissionRequirement::empty(),
                teachingDegreeHigherSemester: AdmissionRequirement::empty(),
                master: AdmissionRequirement::empty(),
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
            examinationRegulations: MultilingualString::empty(),
            moduleHandbook: '',
            url: MultilingualString::empty(),
            department: MultilingualString::empty(),
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
            notesForInternationalApplicants: MultilingualLink::empty(),
        );
    }
}
