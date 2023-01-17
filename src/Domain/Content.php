<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

/**
 * @psalm-import-type ContentItemType from ContentItem
 * @psalm-type ContentType = array{
 *     about: ContentItemType,
 *     structure: ContentItemType,
 *     specializations: ContentItemType,
 *     qualities_and_skills: ContentItemType,
 *     why_should_study: ContentItemType,
 *     career_prospects: ContentItemType,
 *     special_features: ContentItemType,
 *     testimonials: ContentItemType,
 * }
 */
final class Content
{
    public const ABOUT = 'about';
    public const STRUCTURE = 'structure';
    public const SPECIALIZATIONS = 'specializations';
    public const QUALITIES_AND_SKILLS = 'qualities_and_skills';
    public const WHY_SHOULD_STUDY = 'why_should_study';
    public const CAREER_PROSPECTS = 'career_prospects';
    public const SPECIAL_FEATURES = 'special_features';
    public const TESTIMONIALS = 'testimonials';

    /**
     * @phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    private function __construct(
        private ContentItem $about,
        private ContentItem $structure,
        private ContentItem $specializations,
        private ContentItem $qualitiesAndSkills,
        private ContentItem $whyShouldStudy,
        private ContentItem $careerProspects,
        private ContentItem $specialFeatures,
        private ContentItem $testimonials,
    ) {

        $this->about = $this->about->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Worum geht es im Studiengang?',
                'What is the degree program about?',
            )
        );

        $this->structure = $this->structure->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Aufbau und Struktur',
                'Design and structure',
            )
        );

        $this->specializations = $this->specializations->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Studienrichtungen und Schwerpunkte',
                'Fields of study and specializations',
            )
        );

        $this->qualitiesAndSkills = $this->qualitiesAndSkills->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Was sollte ich mitbringen?',
                'Which qualities and skills do I need?',
            )
        );

        $this->whyShouldStudy = $this->whyShouldStudy->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Gute Gründe für ein Studium an der FAU',
                'Why should I study at FAU?',
            )
        );

        $this->careerProspects = $this->careerProspects->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Welche beruflichen Perspektiven stehen mir offen?',
                'Which career prospects are open to me?',
            )
        );

        $this->specialFeatures = $this->specialFeatures->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Besondere Hinweise',
                'Special features',
            )
        );

        $this->specialFeatures = $this->specialFeatures->withDefaultTitle(
            MultilingualString::fromTranslations(
                'default',
                'Erfahrungsberichte',
                'Testimonials',
            )
        );
    }

    public static function new(
        ContentItem $about,
        ContentItem $structure,
        ContentItem $specializations,
        ContentItem $qualitiesAndSkills,
        ContentItem $whyShouldStudy,
        ContentItem $careerProspects,
        ContentItem $specialFeatures,
        ContentItem $testimonials
    ): self {

        return new self(
            $about,
            $structure,
            $specializations,
            $qualitiesAndSkills,
            $whyShouldStudy,
            $careerProspects,
            $specialFeatures,
            $testimonials
        );
    }

    /**
     * @psalm-param ContentType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ContentItem::fromArray($data[self::ABOUT]),
            ContentItem::fromArray($data[self::STRUCTURE]),
            ContentItem::fromArray($data[self::SPECIALIZATIONS]),
            ContentItem::fromArray($data[self::QUALITIES_AND_SKILLS]),
            ContentItem::fromArray($data[self::WHY_SHOULD_STUDY]),
            ContentItem::fromArray($data[self::CAREER_PROSPECTS]),
            ContentItem::fromArray($data[self::SPECIAL_FEATURES]),
            ContentItem::fromArray($data[self::TESTIMONIALS]),
        );
    }

    /**
     * @return ContentType
     */
    public function asArray(): array
    {
        return [
            self::ABOUT => $this->about->asArray(),
            self::STRUCTURE => $this->structure->asArray(),
            self::SPECIALIZATIONS => $this->specializations->asArray(),
            self::QUALITIES_AND_SKILLS => $this->qualitiesAndSkills->asArray(),
            self::WHY_SHOULD_STUDY => $this->whyShouldStudy->asArray(),
            self::CAREER_PROSPECTS => $this->careerProspects->asArray(),
            self::SPECIAL_FEATURES => $this->specialFeatures->asArray(),
            self::TESTIMONIALS => $this->testimonials->asArray(),
        ];
    }

    public function about(): ContentItem
    {
        return $this->about;
    }

    public function structure(): ContentItem
    {
        return $this->structure;
    }

    public function specializations(): ContentItem
    {
        return $this->specializations;
    }

    public function qualitiesAndSkills(): ContentItem
    {
        return $this->qualitiesAndSkills;
    }

    public function whyShouldStudy(): ContentItem
    {
        return $this->whyShouldStudy;
    }

    public function careerProspects(): ContentItem
    {
        return $this->careerProspects;
    }

    public function specialFeatures(): ContentItem
    {
        return $this->specialFeatures;
    }

    public function testimonials(): ContentItem
    {
        return $this->testimonials;
    }
}
