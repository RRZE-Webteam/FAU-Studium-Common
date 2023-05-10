<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Validator;

use Fau\DegreeProgram\Common\Application\ConditionalFieldsFilter;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramDataValidator;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\Violation;
use Fau\DegreeProgram\Common\Domain\Violations;
use Fau\DegreeProgram\Common\Infrastructure\Repository\FacultyRepository;

/**
 * @psalm-import-type DegreeProgramViewRawArrayType from DegreeProgramViewRaw
 */
final class ConditionalFieldsValidator implements DegreeProgramDataValidator
{
    public function __construct(
        private FacultyRepository $facultyRepository,
    ) {
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function validatePublish(array $data): Violations
    {
        /** @var DegreeProgramViewRawArrayType $data */
        $raw = DegreeProgramViewRaw::fromArray($data);
        $violations = Violations::new();

        if ($raw->isFeeRequired()) {
            $violations->add(
                ...self::validateRequiredMultilingualString(
                    $raw->degreeProgramFees(),
                    DegreeProgram::DEGREE_PROGRAM_FEES
                )
            );
        }

        if (ConditionalFieldsFilter::isMasterContext($raw->degree())) {
            !$raw->admissionRequirements()->master()->isEmpty()
            or $violations->add(self::makeEmptyFieldViolation(
                DegreeProgram::ADMISSION_REQUIREMENTS,
                AdmissionRequirements::MASTER
            ));

            $violations->add(
                ...self::validateRequiredMultilingualString(
                    $raw->contentRelatedMasterRequirements(),
                    DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS
                )
            );
        }

        if (
            ConditionalFieldsFilter::isBachelorOrTeachingDegreeContext($raw->degree())
            && !ConditionalFieldsFilter::isAdditionalDegree($raw->degree())
        ) {
            !$raw->admissionRequirements()->bachelorOrTeachingDegree()->isEmpty()
                or $violations->add(self::makeEmptyFieldViolation(
                    DegreeProgram::ADMISSION_REQUIREMENTS,
                    AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE
                ));

            ($raw->admissionRequirements()
                ->bachelorOrTeachingDegree()
                ->hasGermanName(ConditionalFieldsFilter::ADMISSION_REQUIREMENT_FREE)
                || !$raw->admissionRequirements()->teachingDegreeHigherSemester()->isEmpty())
            or $violations->add(self::makeEmptyFieldViolation(
                DegreeProgram::ADMISSION_REQUIREMENTS,
                AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER
            ));
        }

        if (
            ConditionalFieldsFilter::isLanguageSkillForFacultyOfHumanitiesOnlyEnabled(
                $this->facultyRepository->findFacultySlugs($raw),
                $raw->degree()
            )
        ) {
            $raw->languageSkillsHumanitiesFaculty()
                or $violations->add(self::makeEmptyFieldViolation(
                    DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY,
                ));
        }

        return $violations;
    }

    private static function makeEmptyFieldViolation(string $root, string $path = ''): Violation
    {
        $paths = [$root];
        if ($path) {
            $paths[] = $path;
        }

        return Violation::new(
            implode('.', $paths),
            _x(
                'This field can not be empty.',
                'backoffice: REST API error message',
                'fau-degree-program-common'
            ),
            'rest_too_short'
        );
    }

    private static function validateRequiredMultilingualString(
        MultilingualString $multilingualString,
        string $root
    ): Violations {

        $violations = Violations::new();
        $multilingualString->inGerman()
            or $violations->add(self::makeEmptyFieldViolation($root, MultilingualString::DE));
        $multilingualString->inEnglish()
            or $violations->add(self::makeEmptyFieldViolation($root, MultilingualString::EN));

        return $violations;
    }

    public function validateDraft(array $data): Violations
    {
        return Violations::new();
    }
}
