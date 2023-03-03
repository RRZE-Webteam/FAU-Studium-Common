<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Validator;

use Fau\DegreeProgram\Common\Domain\AdmissionRequirements;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\ContentItem;
use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramDataValidator;
use Fau\DegreeProgram\Common\Domain\Image;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;

final class JsonSchemaDegreeProgramDataValidator implements DegreeProgramDataValidator
{
    private const IMAGE = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [Image::ID, Image::URL],
        'properties' => [
            Image::ID => [
                'type' => 'integer',
                'minimum' => 0,
            ],
            Image::URL => [
                'type' => 'string',
            ],
        ],
    ];

    private const MULTILINGUAL_STRING = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['id', MultilingualString::DE, MultilingualString::EN],
        'properties' => [
            'id' => [
                'type' => 'string',
            ],
            MultilingualString::DE => [
                'type' => 'string',
            ],
            MultilingualString::EN => [
                'type' => 'string',
            ],
        ],
    ];

    private const MULTILINGUAL_LIST = [
        'type' => 'array',
        'items' => self::MULTILINGUAL_STRING,
    ];

    private const MULTILINGUAL_LINK = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [
            MultilingualLink::ID,
            MultilingualLink::NAME,
            MultilingualLink::LINK_TEXT,
            MultilingualLink::LINK_URL,
        ],
        'properties' => [
            MultilingualLink::ID => [
                'type' => 'string',
            ],
            MultilingualLink::NAME => self::MULTILINGUAL_STRING,
            MultilingualLink::LINK_TEXT => self::MULTILINGUAL_STRING,
            MultilingualLink::LINK_URL => self::MULTILINGUAL_STRING,
        ],
    ];

    private const MULTILINGUAL_LINKS = [
        'type' => 'array',
        'items' => self::MULTILINGUAL_LINK,
    ];

    private const CONTENT_ITEM = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => [ContentItem::TITLE, ContentItem::DESCRIPTION],
        'properties' => [
            ContentItem::TITLE => self::MULTILINGUAL_STRING,
            ContentItem::DESCRIPTION => self::MULTILINGUAL_STRING,
        ],
    ];

    private const ARRAY_OF_IDS = [
        'type' => 'array',
        'items' => [
            'type' => 'integer',
            'minimum' => 0,
        ],
    ];

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
            DegreeProgram::STUDENT_REPRESENTATIVES,
            DegreeProgram::SEMESTER_FEE,
            DegreeProgram::DEGREE_PROGRAM_FEES,
            DegreeProgram::ABROAD_OPPORTUNITIES,
            DegreeProgram::KEYWORDS,
            DegreeProgram::AREA_OF_STUDY,
            DegreeProgram::COMBINATIONS,
            DegreeProgram::LIMITED_COMBINATIONS,
        ],
        'properties' => [
            DegreeProgram::ID => [
                'type' => 'integer',
                'minimum' => 1,
            ],
            DegreeProgram::SLUG => self::MULTILINGUAL_STRING,
            DegreeProgram::FEATURED_IMAGE => self::IMAGE,
            DegreeProgram::TEASER_IMAGE => self::IMAGE,
            DegreeProgram::TITLE => self::MULTILINGUAL_STRING,
            DegreeProgram::SUBTITLE => self::MULTILINGUAL_STRING,
            DegreeProgram::STANDARD_DURATION => [
                'type' => 'integer',
                'minimum' => 0,
            ],
            DegreeProgram::FEE_REQUIRED => [
                'type' => 'boolean',
            ],
            DegreeProgram::START => self::MULTILINGUAL_LIST,
            DegreeProgram::NUMBER_OF_STUDENTS => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => [NumberOfStudents::ID, NumberOfStudents::DESCRIPTION],
                'properties' => [
                    NumberOfStudents::ID => [
                        'type' => 'string',
                    ],
                    NumberOfStudents::DESCRIPTION => [
                        'type' => 'string',
                    ],
                ],
            ],
            DegreeProgram::TEACHING_LANGUAGE => self::MULTILINGUAL_STRING,
            DegreeProgram::ATTRIBUTES => self::MULTILINGUAL_LIST,
            DegreeProgram::DEGREE => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => [Degree::ID, Degree::NAME, Degree::ABBREVIATION],
                'properties' => [
                    Degree::ID => [
                        'type' => 'string',
                    ],
                    Degree::NAME => self::MULTILINGUAL_STRING,
                    Degree::ABBREVIATION => self::MULTILINGUAL_STRING,
                    Degree::PARENT => [
                        'oneOf' => [
                            [
                                'type' => 'object',
                                'additionalProperties' => true,
                                'required' => [Degree::ID],
                                'properties' => [
                                    Degree::ID => [
                                        'type' => 'string',
                                    ],
                                ],
                            ],
                            [
                                'type' => 'null',
                            ],
                        ],
                    ],
                ],
            ],
            DegreeProgram::FACULTY => self::MULTILINGUAL_LINKS,
            DegreeProgram::LOCATION => self::MULTILINGUAL_LIST,
            DegreeProgram::SUBJECT_GROUPS => self::MULTILINGUAL_LIST,
            DegreeProgram::VIDEOS => [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                ],
            ],
            DegreeProgram::META_DESCRIPTION => self::MULTILINGUAL_STRING,
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
                    Content::ABOUT => self::CONTENT_ITEM,
                    Content::STRUCTURE => self::CONTENT_ITEM,
                    Content::SPECIALIZATIONS => self::CONTENT_ITEM,
                    Content::QUALITIES_AND_SKILLS => self::CONTENT_ITEM,
                    Content::WHY_SHOULD_STUDY => self::CONTENT_ITEM,
                    Content::CAREER_PROSPECTS => self::CONTENT_ITEM,
                    Content::SPECIAL_FEATURES => self::CONTENT_ITEM,
                    Content::TESTIMONIALS => self::CONTENT_ITEM,
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
                    AdmissionRequirements::BACHELOR_OR_TEACHING_DEGREE => self::MULTILINGUAL_LINK,
                    AdmissionRequirements::TEACHING_DEGREE_HIGHER_SEMESTER => self::MULTILINGUAL_LINK,
                    AdmissionRequirements::MASTER => self::MULTILINGUAL_LINK,
                ],
            ],
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS => self::MULTILINGUAL_STRING,
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER => [
                'type' => 'string',
            ],
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => [
                'type' => 'string',
            ],
            DegreeProgram::DETAILS_AND_NOTES => self::MULTILINGUAL_STRING,
            DegreeProgram::LANGUAGE_SKILLS => self::MULTILINGUAL_STRING,
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY => [
                'type' => 'string',
            ],
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS => self::MULTILINGUAL_LINK,
            DegreeProgram::START_OF_SEMESTER => self::MULTILINGUAL_LINK,
            DegreeProgram::SEMESTER_DATES => self::MULTILINGUAL_LINK,
            DegreeProgram::EXAMINATIONS_OFFICE => self::MULTILINGUAL_LINK,
            DegreeProgram::EXAMINATION_REGULATIONS => self::MULTILINGUAL_LINK,
            DegreeProgram::MODULE_HANDBOOK => [
                'type' => 'string',
            ],
            DegreeProgram::URL => self::MULTILINGUAL_STRING,
            DegreeProgram::DEPARTMENT => self::MULTILINGUAL_LINK,
            DegreeProgram::STUDENT_ADVICE => self::MULTILINGUAL_LINK,
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => self::MULTILINGUAL_LINK,
            DegreeProgram::SERVICE_CENTERS => self::MULTILINGUAL_LINK,
            DegreeProgram::STUDENT_REPRESENTATIVES => [
                'type' => 'string',
            ],
            DegreeProgram::SEMESTER_FEE => self::MULTILINGUAL_LINK,
            DegreeProgram::DEGREE_PROGRAM_FEES => self::MULTILINGUAL_STRING,
            DegreeProgram::ABROAD_OPPORTUNITIES => self::MULTILINGUAL_LINK,
            DegreeProgram::KEYWORDS => self::MULTILINGUAL_LIST,
            DegreeProgram::AREA_OF_STUDY => self::MULTILINGUAL_LINKS,
            DegreeProgram::COMBINATIONS => self::ARRAY_OF_IDS,
            DegreeProgram::LIMITED_COMBINATIONS => self::ARRAY_OF_IDS,
        ],
    ];

    public function validate(array $data): ArrayOfStrings
    {
        $result = rest_validate_value_from_schema($data, self::SCHEMA, 'degree_program');
        if ($result === true) {
            return ArrayOfStrings::new();
        }

        return ArrayOfStrings::new(...$result->get_error_messages());
    }
}
