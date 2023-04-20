<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Validator;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\ContentItem;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramDataValidator;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\Domain\Violation;
use Fau\DegreeProgram\Common\Domain\Violations;
use WP_Error;

final class JsonSchemaDegreeProgramDataValidator implements DegreeProgramDataValidator
{
    private const ARRAY_OF_IDS = [
        'type' => 'array',
        'items' => [
            'type' => 'integer',
            'minimum' => 0,
        ],
    ];

    private const DEADLINE_PATTERN = '^((0[1-9]|[12][0-9]|3[01])\.(0[13578]|1[02])|(0[1-9]|[12][0-9]|30)\.(0[469]|11)|(0[1-9]|1[0-9]|2[0-8])\.02)\.$|^$';

    public const SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
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
        ],
        'properties' => [
            DegreeProgram::ID => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            DegreeProgram::SLUG => MultilingualString::SCHEMA,
            DegreeProgram::FEATURED_IMAGE => Image::SCHEMA_REQUIRED,
            DegreeProgram::TEASER_IMAGE => Image::SCHEMA_REQUIRED,
            DegreeProgram::TITLE => MultilingualString::SCHEMA_REQUIRED,
            DegreeProgram::SUBTITLE => MultilingualString::SCHEMA,
            DegreeProgram::STANDARD_DURATION => [
                'type' => 'string',
                'minLength' => 1,
            ],
            DegreeProgram::FEE_REQUIRED => [
                'type' => 'boolean',
            ],
            DegreeProgram::START => MultilingualList::SCHEMA_REQUIRED,
            DegreeProgram::NUMBER_OF_STUDENTS => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => [NumberOfStudents::ID, NumberOfStudents::DESCRIPTION],
                'properties' => [
                    NumberOfStudents::ID => [
                        'type' => 'string',
                        'minLength' => 1,
                    ],
                    NumberOfStudents::DESCRIPTION => [
                        'type' => 'string',
                    ],
                ],
            ],
            DegreeProgram::TEACHING_LANGUAGE => MultilingualString::SCHEMA_ID_REQUIRED,
            DegreeProgram::ATTRIBUTES => MultilingualList::SCHEMA_REQUIRED,
            DegreeProgram::DEGREE => Degree::SCHEMA,
            DegreeProgram::FACULTY => MultilingualLinks::SCHEMA_REQUIRED,
            DegreeProgram::LOCATION => MultilingualList::SCHEMA_REQUIRED,
            DegreeProgram::SUBJECT_GROUPS => MultilingualList::SCHEMA_REQUIRED,
            DegreeProgram::VIDEOS => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            DegreeProgram::META_DESCRIPTION => MultilingualString::SCHEMA_REQUIRED,
            DegreeProgram::CONTENT => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => [
                    Content::ABOUT,
                    Content::STRUCTURE,
                    Content::SPECIALIZATIONS,
                    Content::QUALITIES_AND_SKILLS,
                    Content::WHY_SHOULD_STUDY,
                    Content::CAREER_PROSPECTS,
                    Content::SPECIAL_FEATURES,
                    Content::TESTIMONIALS,
                ],
                'properties' => [
                    Content::ABOUT => ContentItem::SCHEMA_REQUIRED,
                    Content::STRUCTURE => ContentItem::SCHEMA_REQUIRED,
                    Content::SPECIALIZATIONS => ContentItem::SCHEMA,
                    Content::QUALITIES_AND_SKILLS => ContentItem::SCHEMA,
                    Content::WHY_SHOULD_STUDY => ContentItem::SCHEMA,
                    Content::CAREER_PROSPECTS => ContentItem::SCHEMA,
                    Content::SPECIAL_FEATURES => ContentItem::SCHEMA,
                    Content::TESTIMONIALS => ContentItem::SCHEMA,
                ],
            ],
            DegreeProgram::ADMISSION_REQUIREMENTS => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => [
                    AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE,
                    AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER,
                    AdmissionRequirements::MASTER,
                ],
                'properties' => [
                    AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE => [
                        'oneOf' => [
                            AdmissionRequirement::SCHEMA,
                            AdmissionRequirement::SCHEMA_EMPTY,
                        ],
                    ],
                    AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER => [
                        'oneOf' => [
                            AdmissionRequirement::SCHEMA,
                            AdmissionRequirement::SCHEMA_EMPTY,
                        ],
                    ],
                    AdmissionRequirements::MASTER => [
                        'oneOf' => [
                            AdmissionRequirement::SCHEMA,
                            AdmissionRequirement::SCHEMA_EMPTY,
                        ],
                    ],
                ],
            ],
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS => MultilingualString::SCHEMA,
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER => [
                'type' => 'string',
                'pattern' => self::DEADLINE_PATTERN,
            ],
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => [
                'type' => 'string',
                'pattern' => self::DEADLINE_PATTERN,
            ],
            DegreeProgram::DETAILS_AND_NOTES => MultilingualString::SCHEMA_REQUIRED,
            DegreeProgram::LANGUAGE_SKILLS => MultilingualString::SCHEMA_REQUIRED,
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY => [
                'type' => 'string',
            ],
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS => MultilingualLink::SCHEMA_REQUIRED,
            DegreeProgram::START_OF_SEMESTER => MultilingualLink::SCHEMA,
            DegreeProgram::SEMESTER_DATES => MultilingualLink::SCHEMA,
            DegreeProgram::EXAMINATIONS_OFFICE => MultilingualLink::SCHEMA_REQUIRED,
            DegreeProgram::EXAMINATION_REGULATIONS => [
                'type' => 'string',
                'minLength' => 1,
                'format' => 'uri',
            ],
            DegreeProgram::MODULE_HANDBOOK => [
                'type' => 'string',
                'minLength' => 1,
                'format' => 'uri',
            ],
            DegreeProgram::URL => MultilingualString::SCHEMA_URL_REQUIRED,
            DegreeProgram::DEPARTMENT => MultilingualString::SCHEMA_URL_REQUIRED,
            DegreeProgram::STUDENT_ADVICE => MultilingualLink::SCHEMA,
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => MultilingualLink::SCHEMA_REQUIRED,
            DegreeProgram::SERVICE_CENTERS => MultilingualLink::SCHEMA,
            DegreeProgram::INFO_BROCHURE => [
                'type' => 'string',
                'minLength' => 1,
                'format' => 'uri',
            ],
            DegreeProgram::SEMESTER_FEE => MultilingualLink::SCHEMA,
            DegreeProgram::DEGREE_PROGRAM_FEES => MultilingualString::SCHEMA,
            DegreeProgram::ABROAD_OPPORTUNITIES => MultilingualLink::SCHEMA,
            DegreeProgram::KEYWORDS => MultilingualList::SCHEMA_REQUIRED,
            DegreeProgram::AREA_OF_STUDY => MultilingualLinks::SCHEMA_REQUIRED,
            DegreeProgram::COMBINATIONS => self::ARRAY_OF_IDS,
            DegreeProgram::LIMITED_COMBINATIONS => self::ARRAY_OF_IDS,
            DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS => MultilingualLink::SCHEMA,
            DegreeProgram::STUDENT_INITIATIVES => MultilingualLink::SCHEMA,
            DegreeProgram::APPLY_NOW_LINK => MultilingualLink::SCHEMA_REQUIRED,
            DegreeProgram::ENTRY_TEXT => MultilingualString::SCHEMA_REQUIRED,
        ],
    ];

    /**
     * @param array<string, mixed> $data
     */
    public function validate(array $data): Violations
    {
        $violations = Violations::new();

        $generalResult = rest_validate_value_from_schema(
            $data,
            self::SCHEMA,
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
            self::SCHEMA['properties'],
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

            if ($fieldSchema['type'] === 'object' && ! empty($fieldSchema['properties'])) {
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
}
