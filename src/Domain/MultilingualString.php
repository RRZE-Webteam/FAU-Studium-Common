<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Domain;

use JsonSerializable;

/**
 * @psalm-type LanguageCodes = 'de' | 'en'
 * @psalm-type MultilingualStringType = array{id: string, de: string, en: string}
 */
final class MultilingualString implements JsonSerializable
{
    public const DE = 'de';
    public const EN = 'en';

    public const SCHEMA = [
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

    public const SCHEMA_REQUIRED = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['id', MultilingualString::DE, MultilingualString::EN],
        'properties' => [
            'id' => [
                'type' => 'string',
            ],
            MultilingualString::DE => [
                'type' => 'string',
                'minLength' => 1,
            ],
            MultilingualString::EN => [
                'type' => 'string',
                'minLength' => 1,
            ],
        ],
    ];

    public const SCHEMA_URL_REQUIRED = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['id', MultilingualString::DE, MultilingualString::EN],
        'properties' => [
            'id' => [
                'type' => 'string',
            ],
            MultilingualString::DE => [
                'type' => 'string',
                'minLength' => 1,
                'format' => 'uri',
            ],
            MultilingualString::EN => [
                'type' => 'string',
                'minLength' => 1,
                'format' => 'uri',
            ],
        ],
    ];

    public const SCHEMA_ID_REQUIRED = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['id', MultilingualString::DE, MultilingualString::EN],
        'properties' => [
            'id' => [
                'type' => 'string',
                'minLength' => 1,
            ],
            MultilingualString::DE => [
                'type' => 'string',
            ],
            MultilingualString::EN => [
                'type' => 'string',
            ],
        ],
    ];

    public const LANGUAGES = [
        self::DE => 'Deutsch',
        self::EN => 'English',
    ];

    /** @var array{de: string, en: string} */
    private array $translations;

    private function __construct(
        private string $id,
        string $de,
        string $en,
    ) {

        $this->translations = [
            self::DE => $de,
            self::EN => $en,
        ];
    }

    public static function empty(): self
    {
        return new self('', '', '');
    }

    public static function fromTranslations(string $id, string $de, string $en): self
    {
        return new self($id, $de, $en);
    }

    /**
     * @psalm-param MultilingualStringType $translations
     */
    public static function fromArray(array $translations): self
    {
        return new self(
            $translations['id'],
            $translations[self::DE],
            $translations[self::EN],
        );
    }

    /**
     * @return MultilingualStringType
     */
    public function asArray(): array
    {
        return array_merge(
            ['id' => $this->id],
            $this->translations
        );
    }

    /**
     * @psalm-param callable(string): string $callback
     */
    public function mapTranslations(callable $callback): self
    {
        return self::fromTranslations(
            $this->id,
            $callback($this->inGerman()),
            $callback($this->inEnglish()),
        );
    }

    public function asString(string $languageCode): string
    {
        if ($languageCode) {
            return $this->translations[$languageCode] ?? '';
        }

        return $this->translations[self::DE] ?: $this->translations[self::EN];
    }

    public function id(): string
    {
        return $this->id;
    }

    public function inGerman(): string
    {
        return $this->translations[self::DE] ?? '';
    }

    public function inEnglish(): string
    {
        return $this->translations[self::EN] ?? '';
    }

    public function jsonSerialize(): array
    {
        return $this->asArray();
    }

    public function mergeWithDefault(MultilingualString $multilingualString): self
    {
        return new self(
            $this->id ?: $multilingualString->id,
            $this->translations[self::DE] ?: $multilingualString->translations[self::DE],
            $this->translations[self::EN] ?: $multilingualString->translations[self::EN],
        );
    }
}
