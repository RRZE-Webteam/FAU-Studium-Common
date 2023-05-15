<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\ContentItem;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\Infrastructure\Validator\JsonSchemaDegreeProgramDataValidator;

return [
    'type' => 'object',
    'additionalProperties' => false,
    'required' => JsonSchemaDegreeProgramDataValidator::REQUIRED_PROPERTIES,
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
        DegreeProgram::NUMBER_OF_STUDENTS => NumberOfStudents::SCHEMA_REQUIRED,
        DegreeProgram::TEACHING_LANGUAGE => MultilingualString::SCHEMA_ID_REQUIRED,
        DegreeProgram::ATTRIBUTES => MultilingualList::SCHEMA,
        DegreeProgram::DEGREE => Degree::SCHEMA_REQUIRED,
        DegreeProgram::FACULTY => MultilingualLinks::SCHEMA_REQUIRED,
        DegreeProgram::LOCATION => MultilingualList::SCHEMA_REQUIRED,
        DegreeProgram::SUBJECT_GROUPS => MultilingualList::SCHEMA_REQUIRED,
        DegreeProgram::VIDEOS => [
            'type' => 'array',
            'maxItems' => 3,
            'items' => [
                'type' => 'string',
                'format' => 'uri',
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
            'pattern' => JsonSchemaDegreeProgramDataValidator::DEADLINE_PATTERN,
        ],
        DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => [
            'type' => 'string',
            'pattern' => JsonSchemaDegreeProgramDataValidator::DEADLINE_PATTERN,
        ],
        DegreeProgram::DETAILS_AND_NOTES => MultilingualString::SCHEMA,
        DegreeProgram::LANGUAGE_SKILLS => MultilingualString::SCHEMA,
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
            'format' => 'uri',
        ],
        DegreeProgram::SEMESTER_FEE => MultilingualLink::SCHEMA,
        DegreeProgram::DEGREE_PROGRAM_FEES => MultilingualString::SCHEMA,
        DegreeProgram::ABROAD_OPPORTUNITIES => MultilingualLink::SCHEMA,
        DegreeProgram::KEYWORDS => MultilingualList::SCHEMA_REQUIRED,
        DegreeProgram::AREA_OF_STUDY => MultilingualLinks::SCHEMA_REQUIRED,
        DegreeProgram::COMBINATIONS => JsonSchemaDegreeProgramDataValidator::ARRAY_OF_IDS,
        DegreeProgram::LIMITED_COMBINATIONS => JsonSchemaDegreeProgramDataValidator::ARRAY_OF_IDS,
        DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS => MultilingualLink::SCHEMA,
        DegreeProgram::STUDENT_INITIATIVES => MultilingualLink::SCHEMA,
        DegreeProgram::APPLY_NOW_LINK => MultilingualLink::SCHEMA_REQUIRED,
        DegreeProgram::ENTRY_TEXT => MultilingualString::SCHEMA_REQUIRED,
    ],
];
