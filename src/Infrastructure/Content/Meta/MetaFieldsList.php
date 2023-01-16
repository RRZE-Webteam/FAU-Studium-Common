<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Content\Meta;

use ArrayObject;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Infrastructure\Repository\BilingualRepository;

/**
 * @template-extends ArrayObject<int, string>
 */
class MetaFieldsList extends ArrayObject
{
    private function __construct()
    {
        parent::__construct([
            DegreeProgram::TEASER_IMAGE,
            BilingualRepository::addEnglishSuffix('title'),
            DegreeProgram::STANDARD_DURATION,
            DegreeProgram::FEE_REQUIRED,
            DegreeProgram::SUBTITLE,
            BilingualRepository::addEnglishSuffix(DegreeProgram::SUBTITLE),
            DegreeProgram::VIDEOS,
            DegreeProgram::META_DESCRIPTION,
            BilingualRepository::addEnglishSuffix(DegreeProgram::META_DESCRIPTION),
            Content::ABOUT,
            BilingualRepository::addEnglishSuffix(Content::ABOUT),
            Content::STRUCTURE,
            BilingualRepository::addEnglishSuffix(Content::STRUCTURE),
            Content::SPECIALIZATIONS,
            BilingualRepository::addEnglishSuffix(Content::SPECIALIZATIONS),
            Content::QUALITIES_AND_SKILLS,
            BilingualRepository::addEnglishSuffix(Content::QUALITIES_AND_SKILLS),
            Content::WHY_SHOULD_STUDY,
            BilingualRepository::addEnglishSuffix(Content::WHY_SHOULD_STUDY),
            Content::CAREER_PROSPECTS,
            BilingualRepository::addEnglishSuffix(Content::CAREER_PROSPECTS),
            Content::SPECIAL_FEATURES,
            BilingualRepository::addEnglishSuffix(Content::SPECIAL_FEATURES),
            Content::TESTIMONIALS,
            BilingualRepository::addEnglishSuffix(Content::TESTIMONIALS),
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS,
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER,
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER,
            DegreeProgram::DETAILS_AND_NOTES,
            BilingualRepository::addEnglishSuffix(DegreeProgram::DETAILS_AND_NOTES),
            DegreeProgram::LANGUAGE_SKILLS,
            BilingualRepository::addEnglishSuffix(DegreeProgram::LANGUAGE_SKILLS),
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY,
            DegreeProgram::MODULE_HANDBOOK,
            DegreeProgram::URL,
            BilingualRepository::addEnglishSuffix(DegreeProgram::URL),
            DegreeProgram::STUDENT_REPRESENTATIVES,
            DegreeProgram::DEGREE_PROGRAM_FEES,
            BilingualRepository::addEnglishSuffix(DegreeProgram::DEGREE_PROGRAM_FEES),
        ]);
    }

    public static function new(): self
    {
        return new self();
    }
}
