<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\LanguageExtension\ArrayOfStrings;
use RuntimeException;
use WP_Post;
use WP_Term;

final class IdGenerator
{
    public function generateTermId(WP_Term|int $term, string $field = ''): string
    {
        return $this->generateId(
            'term',
            $term instanceof WP_Term ? $term->term_id : $term,
            $field
        );
    }

    public function generateTermMetaId(WP_Term|int $term, string $key): string
    {
        return $this->generateId(
            'term_meta',
            $term instanceof WP_Term ? $term->term_id : $term,
            $key
        );
    }

    public function generatePostId(WP_Post|int $post, string $field = ''): string
    {
        return $this->generateId(
            'post',
            $post instanceof WP_Post ? $post->ID : $post,
            $field
        );
    }

    public function generatePostMetaId(WP_Post|int $post, string $key): string
    {
        return $this->generateId(
            'post_meta',
            $post instanceof WP_Post ? $post->ID : $post,
            $key
        );
    }

    public function generateOptionId(string $optionKey, string $subField = ''): string
    {
        return $this->generateId('option', $optionKey, $subField);
    }

    /**
     * @psalm-param 'post' | 'post_meta' | 'term' | 'term_meta' | 'option' $type
     */
    private function generateId(string $type, int|string $entityId, string $subField = ''): string
    {
        $parts = [
            $type,
            (string) $entityId,
        ];
        if ($subField) {
            $parts[] = $subField;
        }

        return implode(':', $parts);
    }

    /**
     * @psalm-return array{type: string, entityId: string, subField: string|null}
     */
    public function parseId(string $id): array
    {
        $parts = explode(':', $id);

        return [
            'type' => $parts[0] ?? '',
            'entityId' => $parts[1] ?? '',
            'subField' => $parts[2] ?? null,
        ];
    }

    /**
     * @return list<int>
     */
    public function termIdsList(
        MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|NumberOfStudents|Degree $structure
    ): array {

        $ids = $this->retrieveIds($structure);

        $validatedIds = [];
        foreach ($ids as $id) {
            if (!$id) {
                continue;
            }

            ['type' => $type, 'entityId' => $termId] = $this->parseId($id);
            if ($type !== 'term' || (int) $termId < 1) {
                throw new RuntimeException(
                    sprintf(
                        'Could not assign %s structure %s as terms.',
                        $structure::class,
                        (string) json_encode($structure)
                    )
                );
            }

            $validatedIds[] = (int) $termId;
        }

        return $validatedIds;
    }

    private function retrieveIds(
        MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|NumberOfStudents|Degree $structure
    ): ArrayOfStrings {

        if ($structure instanceof MultilingualList || $structure instanceof MultilingualLinks) {
            return ArrayOfStrings::new(
                ...array_map(
                    static fn(MultilingualString|MultilingualLink $item) => $item->id(),
                    $structure->getArrayCopy()
                )
            );
        }

        return ArrayOfStrings::new($structure->id());
    }
}
