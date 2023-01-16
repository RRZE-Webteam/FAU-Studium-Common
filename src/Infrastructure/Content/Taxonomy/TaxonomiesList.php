<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy;

use ArrayObject;

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
}
