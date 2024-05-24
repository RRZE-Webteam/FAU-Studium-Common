<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Domain\AdmissionRequirement;
use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\CampoKeys;
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
        DegreeProgram::FEATURED_IMAGE => Image::SCHEMA,
        DegreeProgram::TEASER_IMAGE => Image::SCHEMA,
        DegreeProgram::TITLE => MultilingualString::SCHEMA,
        DegreeProgram::SUBTITLE => MultilingualString::SCHEMA,
        DegreeProgram::STANDARD_DURATION => [
            'type' => 'string',
        ],
        DegreeProgram::FEE_REQUIRED => [
            'type' => 'boolean',
        ],
        DegreeProgram::START => MultilingualList::SCHEMA,
        DegreeProgram::NUMBER_OF_STUDENTS => NumberOfStudents::SCHEMA,
        DegreeProgram::TEACHING_LANGUAGE => MultilingualString::SCHEMA,
        DegreeProgram::ATTRIBUTES => MultilingualList::SCHEMA,
        DegreeProgram::DEGREE => Degree::SCHEMA,
        DegreeProgram::FACULTY => MultilingualLinks::SCHEMA,
        DegreeProgram::LOCATION => MultilingualList::SCHEMA,
        DegreeProgram::SUBJECT_GROUPS => MultilingualList::SCHEMA,
        DegreeProgram::VIDEOS => [
            'type' => 'array',
            'maxItems' => 3,
            'items' => [
                'type' => 'string',
                'format' => 'uri',
            ],
        ],
        DegreeProgram::META_DESCRIPTION => MultilingualString::SCHEMA,
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
                Content::ABOUT => ContentItem::SCHEMA,
                Content::STRUCTURE => ContentItem::SCHEMA,
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
        ],
        DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => [
            'type' => 'string',
        ],
        DegreeProgram::DETAILS_AND_NOTES => MultilingualString::SCHEMA,
        DegreeProgram::LANGUAGE_SKILLS => MultilingualString::SCHEMA,
        DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY => [
            'type' => 'string',
        ],
        DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS => MultilingualLink::SCHEMA,
        DegreeProgram::START_OF_SEMESTER => MultilingualLink::SCHEMA,
        DegreeProgram::SEMESTER_DATES => MultilingualLink::SCHEMA,
        DegreeProgram::EXAMINATIONS_OFFICE => MultilingualLink::SCHEMA,
        DegreeProgram::EXAMINATION_REGULATIONS => [
            'type' => 'string',
            'format' => 'uri',
        ],
        DegreeProgram::MODULE_HANDBOOK => [
            'type' => 'string',
            'format' => 'uri',
        ],
        DegreeProgram::URL => MultilingualString::SCHEMA,
        DegreeProgram::DEPARTMENT => MultilingualString::SCHEMA,
        DegreeProgram::STUDENT_ADVICE => MultilingualLink::SCHEMA,
        DegreeProgram::SUBJECT_SPECIFIC_ADVICE => MultilingualLink::SCHEMA,
        DegreeProgram::SERVICE_CENTERS => MultilingualLink::SCHEMA,
        DegreeProgram::INFO_BROCHURE => [
            'type' => 'string',
            'format' => 'uri',
        ],
        DegreeProgram::SEMESTER_FEE => MultilingualLink::SCHEMA,
        DegreeProgram::DEGREE_PROGRAM_FEES => MultilingualString::SCHEMA,
        DegreeProgram::ABROAD_OPPORTUNITIES => MultilingualLink::SCHEMA,
        DegreeProgram::KEYWORDS => MultilingualList::SCHEMA,
        DegreeProgram::AREA_OF_STUDY => MultilingualLinks::SCHEMA,
        DegreeProgram::COMBINATIONS => JsonSchemaDegreeProgramDataValidator::ARRAY_OF_IDS,
        DegreeProgram::LIMITED_COMBINATIONS => JsonSchemaDegreeProgramDataValidator::ARRAY_OF_IDS,
        DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS => MultilingualLink::SCHEMA,
        DegreeProgram::STUDENT_INITIATIVES => MultilingualLink::SCHEMA,
        DegreeProgram::APPLY_NOW_LINK => MultilingualLink::SCHEMA,
        DegreeProgram::ENTRY_TEXT => MultilingualString::SCHEMA,
        DegreeProgram::CAMPO_KEYS => CampoKeys::SCHEMA,
    ],
];
