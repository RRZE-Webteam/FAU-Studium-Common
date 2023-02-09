<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use JsonSerializable;

final class DegreeProgramViewTranslated implements JsonSerializable
{
    public const LANG = 'lang';
    public const APPLICATION = 'application';
    public const TRANSLATIONS = 'translations';

    /** @var array<string, DegreeProgramViewTranslated> */
    private array $translations = [];

    public function __construct(
        private DegreeProgramId $id,
        private string $lang,
        private Image $featuredImage,
        private Image $teaserImage,
        private string $title,
        private string $subtitle,
        private int $standardDuration,
        private bool $feeRequired,
        private ArrayOfStrings $start,
        private string $numberOfStudents,
        private string $teachingLanguage,
        private ArrayOfStrings $attributes,
        private DegreeTranslated $degree,
        private Link $faculty,
        private string $location,
        private ArrayOfStrings $subjectGroups,
        private ArrayOfStrings $videos,
        private string $metaDescription,
        private ContentTranslated $content,
        /**
         * Bewerbung
         */
        private Link $application,
        private string $contentRelatedMasterRequirements,
        private string $applicationDeadlineWinterSemester,
        private string $applicationDeadlineSummerSemester,
        private string $detailsAndNotes,
        private string $languageSkills,
        private string $languageSkillsHumanitiesFaculty,
        private Link $germanLanguageSkillsForInternationalStudents,
        private Link $startOfSemester,
        private Link $semesterDates,
        private Link $examinationsOffice,
        private Link $examinationRegulations,
        private string $moduleHandbook,
        private string $url,
        private Link $department,
        private Link $studentAdvice,
        private Link $subjectSpecificAdvice,
        private Link $serviceCenters,
        private string $studentRepresentatives,
        private Link $semesterFee,
        private string $degreeProgramFees,
        private Link $abroadOpportunities,
        private RelatedDegreePrograms $combinations,
        private RelatedDegreePrograms $limitedCombinations,
    ) {
    }

    /**
     * We run this method on raw data from persistence
     * so strong typing doesn't make sense.
     *
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedArrayAssignment
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public static function fromArray(array $data): self
    {
        $main = new self(
            id: DegreeProgramId::fromInt((int) $data[DegreeProgram::ID]),
            lang: $data[self::LANG],
            featuredImage: Image::fromArray($data[DegreeProgram::FEATURED_IMAGE]),
            teaserImage: Image::fromArray($data[DegreeProgram::TEASER_IMAGE]),
            title: $data[DegreeProgram::TITLE],
            subtitle: $data[DegreeProgram::SUBTITLE],
            standardDuration: $data[DegreeProgram::STANDARD_DURATION],
            feeRequired: $data[DegreeProgram::FEE_REQUIRED],
            start: ArrayOfStrings::new(...$data[DegreeProgram::START]),
            numberOfStudents: $data[DegreeProgram::NUMBER_OF_STUDENTS],
            teachingLanguage: $data[DegreeProgram::TEACHING_LANGUAGE],
            attributes: ArrayOfStrings::new(...$data[DegreeProgram::ATTRIBUTES]),
            degree: DegreeTranslated::fromArray($data[DegreeProgram::DEGREE]),
            faculty: Link::fromArray($data[DegreeProgram::FACULTY]),
            location: $data[DegreeProgram::LOCATION],
            subjectGroups: ArrayOfStrings::new(...$data[DegreeProgram::SUBJECT_GROUPS]),
            videos: ArrayOfStrings::new(...$data[DegreeProgram::VIDEOS]),
            metaDescription: $data[DegreeProgram::META_DESCRIPTION],
            content: ContentTranslated::fromArray($data[DegreeProgram::CONTENT]),
            application: Link::fromArray($data[self::APPLICATION]),
            contentRelatedMasterRequirements: $data[DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS],
            applicationDeadlineWinterSemester: $data[DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER],
            applicationDeadlineSummerSemester: $data[DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER],
            detailsAndNotes: $data[DegreeProgram::DETAILS_AND_NOTES],
            languageSkills: $data[DegreeProgram::LANGUAGE_SKILLS],
            languageSkillsHumanitiesFaculty: $data[DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY],
            germanLanguageSkillsForInternationalStudents: Link::fromArray($data[DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS]),
            startOfSemester: Link::fromArray($data[DegreeProgram::START_OF_SEMESTER]),
            semesterDates: Link::fromArray($data[DegreeProgram::SEMESTER_DATES]),
            examinationsOffice: Link::fromArray($data[DegreeProgram::EXAMINATIONS_OFFICE]),
            examinationRegulations: Link::fromArray($data[DegreeProgram::EXAMINATION_REGULATIONS]),
            moduleHandbook: $data[DegreeProgram::MODULE_HANDBOOK],
            url: $data[DegreeProgram::URL],
            department: Link::fromArray($data[DegreeProgram::DEPARTMENT]),
            studentAdvice: Link::fromArray($data[DegreeProgram::STUDENT_ADVICE]),
            subjectSpecificAdvice: Link::fromArray($data[DegreeProgram::SUBJECT_SPECIFIC_ADVICE]),
            serviceCenters: Link::fromArray($data[DegreeProgram::SERVICE_CENTERS]),
            studentRepresentatives: $data[DegreeProgram::STUDENT_REPRESENTATIVES],
            semesterFee: Link::fromArray($data[DegreeProgram::SEMESTER_FEE]),
            degreeProgramFees: $data[DegreeProgram::DEGREE_PROGRAM_FEES],
            abroadOpportunities: Link::fromArray($data[DegreeProgram::ABROAD_OPPORTUNITIES]),
            combinations:  RelatedDegreePrograms::fromArray($data[DegreeProgram::COMBINATIONS]),
            limitedCombinations: RelatedDegreePrograms::fromArray($data[DegreeProgram::LIMITED_COMBINATIONS]),
        );

        if (empty($data[self::TRANSLATIONS])) {
            return $main;
        }

        foreach ($data[self::TRANSLATIONS] as $translationData) {
            $translationData[DegreeProgram::ID] = $data[DegreeProgram::ID];
            $main->withTranslation(self::fromArray($translationData), $data[self::LANG]);
        }

        return $main;
    }

    public function asArray(): array
    {
        return [
            DegreeProgram::ID => $this->id->asInt(),
            self::LANG => $this->lang,
            DegreeProgram::FEATURED_IMAGE => $this->featuredImage->asArray(),
            DegreeProgram::TEASER_IMAGE => $this->teaserImage->asArray(),
            DegreeProgram::TITLE => $this->title,
            DegreeProgram::SUBTITLE => $this->subtitle,
            DegreeProgram::STANDARD_DURATION => $this->standardDuration,
            DegreeProgram::FEE_REQUIRED => $this->feeRequired,
            DegreeProgram::START => $this->start->getArrayCopy(),
            DegreeProgram::NUMBER_OF_STUDENTS => $this->numberOfStudents,
            DegreeProgram::TEACHING_LANGUAGE => $this->teachingLanguage,
            DegreeProgram::ATTRIBUTES => $this->attributes->getArrayCopy(),
            DegreeProgram::DEGREE => $this->degree->asArray(),
            DegreeProgram::FACULTY => $this->faculty->asArray(),
            DegreeProgram::LOCATION => $this->location,
            DegreeProgram::SUBJECT_GROUPS => $this->subjectGroups->getArrayCopy(),
            DegreeProgram::VIDEOS => $this->videos->getArrayCopy(),
            DegreeProgram::META_DESCRIPTION => $this->metaDescription,
            DegreeProgram::CONTENT => $this->content->asArray(),
            self::APPLICATION => $this->application->asArray(),
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS => $this->contentRelatedMasterRequirements,
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER => $this->applicationDeadlineWinterSemester,
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => $this->applicationDeadlineSummerSemester,
            DegreeProgram::DETAILS_AND_NOTES => $this->detailsAndNotes,
            DegreeProgram::LANGUAGE_SKILLS => $this->languageSkills,
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY => $this->languageSkillsHumanitiesFaculty,
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS =>
                $this->germanLanguageSkillsForInternationalStudents->asArray(),
            DegreeProgram::START_OF_SEMESTER => $this->startOfSemester->asArray(),
            DegreeProgram::SEMESTER_DATES => $this->semesterDates->asArray(),
            DegreeProgram::EXAMINATIONS_OFFICE => $this->examinationsOffice->asArray(),
            DegreeProgram::EXAMINATION_REGULATIONS => $this->examinationRegulations->asArray(),
            DegreeProgram::MODULE_HANDBOOK => $this->moduleHandbook,
            DegreeProgram::URL => $this->url,
            DegreeProgram::DEPARTMENT => $this->department->asArray(),
            DegreeProgram::STUDENT_ADVICE => $this->studentAdvice->asArray(),
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => $this->subjectSpecificAdvice->asArray(),
            DegreeProgram::SERVICE_CENTERS => $this->serviceCenters->asArray(),
            DegreeProgram::STUDENT_REPRESENTATIVES => $this->studentRepresentatives,
            DegreeProgram::SEMESTER_FEE => $this->semesterFee->asArray(),
            DegreeProgram::DEGREE_PROGRAM_FEES => $this->degreeProgramFees,
            DegreeProgram::ABROAD_OPPORTUNITIES => $this->abroadOpportunities->asArray(),
            DegreeProgram::COMBINATIONS => $this->combinations->asArray(),
            DegreeProgram::LIMITED_COMBINATIONS => $this->limitedCombinations->asArray(),
            self::TRANSLATIONS => $this->translationsAsArray(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->asArray();
    }

    public function withTranslation(
        DegreeProgramViewTranslated $degreeProgramViewTranslated,
        string $languageCode,
    ): self {

        $instance = clone $this;
        $instance->translations[$languageCode] = $degreeProgramViewTranslated;

        return $instance;
    }

    public function withBaseLang(string $languageCode): ?self
    {
        if ($languageCode === $this->lang) {
            return $this;
        }

        if (!isset($this->translations[$languageCode])) {
            return null;
        }

        $main = $this->translations[$languageCode];
        $translation = clone $this;
        $translation->translations = [];
        $main->withTranslation($translation, $languageCode);

        return $main;
    }

    private function translationsAsArray(): array
    {
        return array_map(static function (DegreeProgramViewTranslated $view): array {
            $result = $view->asArray();
            unset($result[DegreeProgram::ID], $result[self::TRANSLATIONS]);

            return $result;
        }, $this->translations);
    }

    public function id(): int
    {
        return $this->id->asInt();
    }
}
