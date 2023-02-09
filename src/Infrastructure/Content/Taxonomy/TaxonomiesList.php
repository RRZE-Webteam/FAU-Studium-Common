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
    }

    public static function new(): self
    {
        return new self();
    }

    public function keys(): ArrayOfStrings
    {
        /** @var array<string>|null $keys */
        static $keys = null;

        if (is_array($keys)) {
            return ArrayOfStrings::new(...$keys);
        }

        $keys = [];
        foreach ($this->getArrayCopy() as $item) {
            if (!defined("{$item}::KEY")) {
                continue;
            }

            $keys[] = (string) constant("{$item}::KEY");
        }

        return ArrayOfStrings::new(...$keys);
    }
}
