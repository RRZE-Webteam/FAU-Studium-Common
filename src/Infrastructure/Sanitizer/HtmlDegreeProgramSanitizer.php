<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Sanitizer;

use Fau\DegreeProgram\Common\Domain\DegreeProgramSanitizer;
use Fau\DegreeProgram\Common\Domain\MultilingualString;

final class HtmlDegreeProgramSanitizer implements DegreeProgramSanitizer
{
    public function sanitizeContentField(string $content): string
    {
        return $this->stripNotAllowedShortcodes(
            wp_kses(
                $content,
                self::ALLOWED_ENTITIES
            )
        );
    }

    public function sanitizeTextField(string $text): string
    {
        return wp_strip_all_tags($text);
    }

    public function sanitizeMultiLingualTextField(MultilingualString $text): MultilingualString
    {
        return MultilingualString::fromTranslations(
            $text->id(),
            wp_strip_all_tags($text->inGerman()),
            wp_strip_all_tags($text->inEnglish()),
        );
    }

    public function sanitizeUrlField(string $url): string
    {
        return filter_var($url, FILTER_VALIDATE_URL) ?: '';
    }

    public function sanitizeMultilingualUrlField(MultilingualString $multilingualUrl): MultilingualString
    {
        return MultilingualString::fromTranslations(
            $multilingualUrl->id(),
            $this->sanitizeUrlField($multilingualUrl->inGerman()),
            $this->sanitizeUrlField($multilingualUrl->inEnglish()),
        );
    }

    private function stripNotAllowedShortcodes(string $content, ?array $allowedShortcodes = null): string
    {
        if (!str_contains($content, '[')) {
            return $content;
        }

        preg_match_all(
            '/' . get_shortcode_regex() . '/',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        if (!$matches) {
            return $content;
        }

        /**
         * @see https://developer.wordpress.org/reference/functions/get_shortcode_regex/
         * @var array $shortcode
         */
        foreach ($matches as $shortcode) {
            $fullShortcodeTag = (string) $shortcode[0];
            $shortcodeTagName = (string) $shortcode[2];
            if (!in_array($shortcodeTagName, $allowedShortcodes ?? self::ALLOWED_SHORTCODES, true)) {
                $content = str_replace($fullShortcodeTag, '', $content);
            }
        }

        return $content;
    }
}
