<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Repository;

use Fau\DegreeProgram\Common\Application\ContentTranslated;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\DegreeTranslated;
use Fau\DegreeProgram\Common\Application\Link;
use Fau\DegreeProgram\Common\Application\RelatedDegreePrograms;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\DegreeProgramRepository;
use Fau\DegreeProgram\Common\Tests\FixtureDegreeProgramDataProviderTrait;
use RuntimeException;

final class StubDegreeProgramRepository implements DegreeProgramRepository, DegreeProgramViewRepository
{
    use FixtureDegreeProgramDataProviderTrait;

    /**
     * @var array<int, DegreeProgram>
     */
    private array $store = [];

    public function getById(DegreeProgramId $degreeProgramId): DegreeProgram
    {
        return $this->store[$degreeProgramId->asInt()]
            ?? throw new RuntimeException(
                'Could not find degree program with id ' . (string) $degreeProgramId->asInt()
            );
    }

    public function save(DegreeProgram $degreeProgram): void
    {
        $raw = DegreeProgramViewRaw::fromDegreeProgram($degreeProgram);
        $this->store[$raw->id()->asInt()] = $degreeProgram;
    }

    public function findRaw(DegreeProgramId $degreeProgramId): ?DegreeProgramViewRaw
    {
        return isset($this->store[$degreeProgramId->asInt()])
            ? DegreeProgramViewRaw::fromDegreeProgram($this->store[$degreeProgramId->asInt()])
            : null;
    }

    public function findTranslated(DegreeProgramId $degreeProgramId, string $languageCode): ?DegreeProgramViewTranslated
    {
        $raw = $this->findRaw($degreeProgramId);
        if (!$raw) {
            return null;
        }

        $raw = $this->findRaw($degreeProgramId);
        if (!$raw instanceof DegreeProgramViewRaw) {
            return null;
        }

        return new DegreeProgramViewTranslated(
            id: $degreeProgramId,
            featuredImage: $raw->featuredImage(),
            teaserImage: $raw->teaserImage(),
            title: $raw->title()->asString($languageCode),
            subtitle: $raw->subtitle()->asString($languageCode),
            standardDuration: $raw->standardDuration(),
            feeRequired: $raw->isFeeRequired(),
            start: $raw->start()->asArrayOfStrings($languageCode),
            numberOfStudents: $raw->numberOfStudents()->asString(),
            teachingLanguage: $raw->teachingLanguage()->asString($languageCode),
            attributes: $raw->attributes()->asArrayOfStrings($languageCode),
            degree: DegreeTranslated::fromDegree($raw->degree(), $languageCode),
            faculty: Link::fromMultilingualLink($raw->faculty(), $languageCode),
            location: $raw->location()->asString($languageCode),
            subjectGroups: $raw->subjectGroups()->asArrayOfStrings($languageCode),
            videos: $raw->videos(),
            metaDescription: $raw->metaDescription()->asString($languageCode),
            content: ContentTranslated::fromContent($raw->content(), $languageCode),
            application: Link::fromMultilingualLink($raw->admissionRequirements()->requirementsForDegree($raw->degree()), $languageCode),
            contentRelatedMasterRequirements: $raw->contentRelatedMasterRequirements()->asString($languageCode),
            applicationDeadlineWinterSemester: $raw->applicationDeadlineWinterSemester(),
            applicationDeadlineSummerSemester: $raw->applicationDeadlineSummerSemester(),
            detailsAndNotes: $raw->detailsAndNotes()->asString($languageCode),
            languageSkills: $raw->languageSkills()->asString($languageCode),
            languageSkillsHumanitiesFaculty: $raw->languageSkillsHumanitiesFaculty(),
            germanLanguageSkillsForInternationalStudents: Link::fromMultilingualLink(
                $raw->germanLanguageSkillsForInternationalStudents(),
                $languageCode
            ),
            startOfSemester: Link::fromMultilingualLink($raw->startOfSemester(), $languageCode),
            semesterDates: Link::fromMultilingualLink($raw->semesterDates(), $languageCode),
            examinationsOffice: Link::fromMultilingualLink($raw->examinationsOffice(), $languageCode),
            examinationRegulations: Link::fromMultilingualLink($raw->examinationRegulations(), $languageCode),
            moduleHandbook: $raw->moduleHandbook(),
            url: $raw->url()->asString($languageCode),
            department: Link::fromMultilingualLink($raw->department(), $languageCode),
            studentAdvice: Link::fromMultilingualLink($raw->studentAdvice(), $languageCode),
            subjectSpecificAdvice: Link::fromMultilingualLink($raw->subjectSpecificAdvice(), $languageCode),
            serviceCenters: Link::fromMultilingualLink($raw->serviceCenters(), $languageCode),
            studentRepresentatives: $raw->studentRepresentatives(),
            semesterFee: Link::fromMultilingualLink($raw->semesterFee(), $languageCode),
            degreeProgramFees: $raw->degreeProgramFees()->asString($languageCode),
            abroadOpportunities: Link::fromMultilingualLink($raw->abroadOpportunities(), $languageCode),
            combinations: RelatedDegreePrograms::new(),
            limitedCombinations: RelatedDegreePrograms::new(),
        );
    }
}
