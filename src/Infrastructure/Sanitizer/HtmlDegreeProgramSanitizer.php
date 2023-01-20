<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Sanitizer;

use Fau\DegreeProgram\Common\Domain\DegreeProgramSanitizer;

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

    private function stripNotAllowedShortcodes(string $content): string
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
            if (!in_array($shortcodeTagName, self::ALLOWED_SHORTCODES, true)) {
                $content = str_replace($fullShortcodeTag, '', $content);
            }
        }

        return $content;
    }
}
