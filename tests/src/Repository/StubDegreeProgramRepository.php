<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Repository;

use Fau\DegreeProgram\Common\Application\AdmissionRequirementsTranslated;
use Fau\DegreeProgram\Common\Application\ContentTranslated;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\DegreeTranslated;
use Fau\DegreeProgram\Common\Application\Link;
use Fau\DegreeProgram\Common\Application\Links;
use Fau\DegreeProgram\Common\Application\RelatedDegreePrograms;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\DegreeProgramRepository;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
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
        if (!$raw instanceof DegreeProgramViewRaw) {
            return null;
        }

        $main = $this->translateDegreeProgram($raw, $languageCode);
        foreach (MultilingualString::LANGUAGES as $code => $name) {
            if ($code === $languageCode) {
                continue;
            }

            $main = $main->withTranslation(
                $this->translateDegreeProgram($raw, $code),
                $code
            );
        }

        return $main;
    }

    private function translateDegreeProgram(
        DegreeProgramViewRaw $raw,
        string $languageCode
    ): DegreeProgramViewTranslated {

        return new DegreeProgramViewTranslated(
            id: $raw->id(),
            link: '',
            slug: '',
            lang: $languageCode,
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
            faculty: Links::fromMultilingualLinks($raw->faculty(), $languageCode),
            location: $raw->location()->asArrayOfStrings($languageCode),
            subjectGroups: $raw->subjectGroups()->asArrayOfStrings($languageCode),
            videos: $raw->videos(),
            metaDescription: $raw->metaDescription()->asString($languageCode),
            content: ContentTranslated::fromContent($raw->content(), $languageCode),
            admissionRequirements: AdmissionRequirementsTranslated::fromAdmissionRequirements(
                $raw->admissionRequirements(),
                $languageCode
            ),
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
            examinationRegulations: $raw->examinationRegulations(),
            moduleHandbook: $raw->moduleHandbook(),
            url: $raw->url()->asString($languageCode),
            department: $raw->department()->asString($languageCode),
            studentAdvice: Link::fromMultilingualLink($raw->studentAdvice(), $languageCode),
            subjectSpecificAdvice: Link::fromMultilingualLink($raw->subjectSpecificAdvice(), $languageCode),
            serviceCenters: Link::fromMultilingualLink($raw->serviceCenters(), $languageCode),
            studentRepresentatives: $raw->studentRepresentatives(),
            semesterFee: Link::fromMultilingualLink($raw->semesterFee(), $languageCode),
            degreeProgramFees: $raw->degreeProgramFees()->asString($languageCode),
            abroadOpportunities: Link::fromMultilingualLink($raw->abroadOpportunities(), $languageCode),
            keywords: $raw->keywords()->asArrayOfStrings($languageCode),
            areaOfStudy: Links::fromMultilingualLinks($raw->areaOfStudy(), $languageCode),
            combinations: RelatedDegreePrograms::new(),
            limitedCombinations: RelatedDegreePrograms::new(),
            notesForInternationalApplicants: Link::fromMultilingualLink($raw->notesForInternationalApplicants(), $languageCode),
            applyNowLink: Link::fromMultilingualLink($raw->applyNowLink(), $languageCode),
            entryText: $raw->entryText()->asString($languageCode),
        );
    }

    public function findRawCollection(CollectionCriteria $criteria): PaginationAwareCollection
    {
        return new StubPaginationAwareCollection();
    }

    public function findTranslatedCollection(CollectionCriteria $criteria, string $languageCode): PaginationAwareCollection
    {
        return new StubPaginationAwareCollection();
    }
}
