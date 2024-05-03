<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Validator;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramDataValidator;
use Fau\DegreeProgram\Common\Domain\Violation;
use Fau\DegreeProgram\Common\Domain\Violations;
use Fau\DegreeProgram\Common\Infrastructure\Sanitizer\SerializedBlocksDegreeProgramSanitizer;
use WP_Error;

/**
 * @psalm-type SchemaType = array{
 *      properties: array
 * }
 */
final class JsonSchemaDegreeProgramDataValidator implements DegreeProgramDataValidator
{
    public const ARRAY_OF_IDS = [
        'type' => 'array',
        'items' => [
            'type' => 'integer',
            'minimum' => 0,
        ],
    ];

    public const DEADLINE_PATTERN = '^((0[1-9]|[12][0-9]|3[01])\.(0[13578]|1[02])|(0[1-9]|[12][0-9]|30)\.(0[469]|11)|(0[1-9]|1[0-9]|2[0-8])\.02)\.$|^$';

    public const REQUIRED_PROPERTIES = [
        DegreeProgram::ID,
        DegreeProgram::SLUG,
        DegreeProgram::FEATURED_IMAGE,
        DegreeProgram::TEASER_IMAGE,
        DegreeProgram::TITLE,
        DegreeProgram::SUBTITLE,
        DegreeProgram::STANDARD_DURATION,
        DegreeProgram::FEE_REQUIRED,
        DegreeProgram::START,
        DegreeProgram::NUMBER_OF_STUDENTS,
        DegreeProgram::TEACHING_LANGUAGE,
        DegreeProgram::ATTRIBUTES,
        DegreeProgram::DEGREE,
        DegreeProgram::FACULTY,
        DegreeProgram::LOCATION,
        DegreeProgram::SUBJECT_GROUPS,
        DegreeProgram::VIDEOS,
        DegreeProgram::META_DESCRIPTION,
        DegreeProgram::CONTENT,
        DegreeProgram::ADMISSION_REQUIREMENTS,
        DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS,
        DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER,
        DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER,
        DegreeProgram::DETAILS_AND_NOTES,
        DegreeProgram::LANGUAGE_SKILLS,
        DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY,
        DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS,
        DegreeProgram::START_OF_SEMESTER,
        DegreeProgram::SEMESTER_DATES,
        DegreeProgram::EXAMINATIONS_OFFICE,
        DegreeProgram::EXAMINATION_REGULATIONS,
        DegreeProgram::MODULE_HANDBOOK,
        DegreeProgram::URL,
        DegreeProgram::DEPARTMENT,
        DegreeProgram::STUDENT_ADVICE,
        DegreeProgram::SUBJECT_SPECIFIC_ADVICE,
        DegreeProgram::SERVICE_CENTERS,
        DegreeProgram::INFO_BROCHURE,
        DegreeProgram::SEMESTER_FEE,
        DegreeProgram::DEGREE_PROGRAM_FEES,
        DegreeProgram::ABROAD_OPPORTUNITIES,
        DegreeProgram::KEYWORDS,
        DegreeProgram::AREA_OF_STUDY,
        DegreeProgram::COMBINATIONS,
        DegreeProgram::LIMITED_COMBINATIONS,
        DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS,
        DegreeProgram::APPLY_NOW_LINK,
    ];

    /**
     * @psalm-param SchemaType $draftSchema
     * @psalm-param SchemaType $publishSchema
     */
    public function __construct(
        private array $draftSchema,
        private array $publishSchema,
        private SerializedBlocksDegreeProgramSanitizer $blocksSanitizer,
    ) {
    }

    public function validatePublish(array $data): Violations
    {
        return $this->validate($data, $this->publishSchema);
    }

    public function validateDraft(array $data): Violations
    {
        return $this->validate($data, $this->draftSchema);
    }

    /**
     * @param array<string, mixed> $data
     * @psalm-param SchemaType $schema
     */
    private function validate(array $data, array $schema): Violations
    {
        $violations = Violations::new();

        // It's important to sanitize content fields before validation to clear fields
        // with empty paragraphs only.
        array_walk_recursive(
            $data,
            function (mixed &$value) {
                $value = is_string($value)
                ? $this->blocksSanitizer->sanitizeContentField($value)
                : $value;
            }
        );
        /** @psalm-var array<string, mixed> $data */

        $generalResult = rest_validate_value_from_schema(
            $data,
            $schema,
            'degree_program',
        );

        if (!$generalResult instanceof WP_Error) {
            return $violations;
        }

        // Find missing required properties violations
        if ($generalResult->get_error_code() === 'rest_property_required') {
            $violations->offsetSet(
                'degree_program',
                Violation::new(
                    'degree_program',
                    $generalResult->get_error_message(),
                    (string) $generalResult->get_error_code(),
                )
            );
        }

        // Go through each property recursively and populate rest of violations.
        $this->populateViolations(
            $data,
            $schema['properties'],
            $violations,
        );

        return $violations;
    }

    /**
     * @param array<string, mixed> $fields
     * @param array $schema
     */
    private function populateViolations(
        mixed $fields,
        array $schema,
        Violations $violations,
        string $prefixKey = '',
    ): void {

        foreach ($fields as $key => $field) {
            /** @var array{properties: array[], type: string} $fieldSchema */
            $fieldSchema = $schema[$key];

            $path = $prefixKey ? $prefixKey . '.' . $key : $key;

            $error = rest_validate_value_from_schema(
                $field,
                $fieldSchema,
                $path,
            );

            if (!$error instanceof WP_Error) {
                continue;
            }

            if (
                $fieldSchema['type'] === 'object'
                && isset($fieldSchema['properties'])
                && $fieldSchema['properties']
                && is_array($field)
            ) {
                /** @var array<string, mixed> $field */
                $this->populateViolations($field, $fieldSchema['properties'], $violations, $path);
                continue;
            }

            /** @var string $errorCode */
            $errorCode = $error->get_error_code();

            $violations->offsetSet(
                $path,
                Violation::new(
                    $path,
                    $error->get_error_message(),
                    $errorCode,
                )
            );
        }
    }

    public function publishSchema(): array
    {
        return $this->publishSchema;
    }

    public function draftSchema(): array
    {
        return $this->draftSchema;
    }
}
