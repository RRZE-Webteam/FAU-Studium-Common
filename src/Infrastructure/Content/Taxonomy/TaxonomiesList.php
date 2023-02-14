<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

use ArrayObject;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;

/**
 * @template-extends ArrayObject<int, class-string<Taxonomy>>
 */
final class TaxonomiesList extends ArrayObject
{
    /**
     * @var array<string, string>
     */
    private array $restBaseSlugMap = [];

    private function __construct()
    {
        parent::__construct([
            AreaOfStudyTaxonomy::class,
            AttributeTaxonomy::class,
            BachelorOrTeachingDegreeAdmissionRequirementTaxonomy::class,
            DegreeTaxonomy::class,
            ExaminationRegulationsTaxonomy::class,
            ExaminationsOfficeTaxonomy::class,
            FacultyTaxonomy::class,
            GermanLanguageSkillsForInternationalStudentsTaxonomy::class,
            KeywordTaxonomy::class,
            MasterDegreeAdmissionRequirementTaxonomy::class,
            NumberOfStudentsTaxonomy::class,
            SemesterTaxonomy::class,
            StudyLocationTaxonomy::class,
            SubjectGroupTaxonomy::class,
            SubjectSpecificAdviceTaxonomy::class,
            TeachingDegreeHigherSemesterAdmissionRequirementTaxonomy::class,
            TeachingLanguageTaxonomy::class,
        ]);

        foreach ($this->getArrayCopy() as $item) {
            if (!defined("{$item}::KEY")) {
                continue;
            }

            $restBase = (string) constant("{$item}::REST_BASE");
            $key = (string) constant("{$item}::KEY");
            $this->restBaseSlugMap[$restBase] = $key;
        }
    }

    public static function new(): self
    {
        return new self();
    }

    public function keys(): ArrayOfStrings
    {
        return ArrayOfStrings::new(...array_values($this->restBaseSlugMap));
    }

    public function convertRestBaseToSlug(string $restBase): ?string
    {
        return $this->restBaseSlugMap[$restBase] ?? null;
    }
}
