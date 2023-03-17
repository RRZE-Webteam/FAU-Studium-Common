<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Sanitizer;

use Fau\DegreeProgram\Common\Domain\DegreeProgramSanitizer;

/**
 * @psalm-type ParsedBlock = array{
 *     blockName: string,
 *     attrs: array<string, mixed>,
 *     innerHTML: string
 * }
 */
final class SerializedBlocksDegreeProgramSanitizer implements DegreeProgramSanitizer
{
    public function sanitizeContentField(string $content): string
    {
        if (!has_blocks($content)) {
            return $content;
        }

        /** @psalm-var array<ParsedBlock> $parsedBlocks */
        $parsedBlocks = parse_blocks($content);
        $result = '';
        foreach ($parsedBlocks as $block) {
            $result .= $this->isValidBlock($block) ? serialize_block($block) : '';
        }

        return $result;
    }

    /**
     * @psalm-param ParsedBlock $block
     */
    private function isValidBlock(array $block): bool
    {
        if (!in_array($block['blockName'], self::ALLOWED_BLOCKS, true)) {
            return false;
        }

        return match ($block['blockName']) {
            'core/heading' => in_array((int) ($block['attrs']['level'] ?? 2), [3, 4, 5], true),
            'core/shortcode' => (bool) preg_match(
                $this->allowedShortcodeRegexpPattern(),
                trim($block['innerHTML'])
            ),
            default => true,
        };
    }

    private function allowedShortcodeRegexpPattern(): string
    {
        return sprintf(
            '#^\[(%s)#',
            implode('|', array_map('preg_quote', self::ALLOWED_SHORTCODES))
        );
    }
}
