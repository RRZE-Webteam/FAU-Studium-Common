<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use RuntimeException;
use WP_Post;
use WP_Term;

abstract class BilingualRepository
{
    private const OPTION_PREFIX = 'fau';

    public function __construct(
        protected IdGenerator $idGenerator,
    ) {
    }

    final protected function bilingualTermName(?WP_Term $term): MultilingualString
    {
        if (!$term instanceof WP_Term) {
            return MultilingualString::empty();
        }

        return MultilingualString::fromTranslations(
            $this->idGenerator->generateTermId($term, 'name'),
            $term->name,
            (string) get_term_meta(
                $term->term_id,
                self::addEnglishSuffix('name'),
                true
            )
        );
    }

    final protected function bilingualTermsList(WP_Post $post, string $taxonomy): MultilingualList
    {
        $terms = get_the_terms($post, $taxonomy);
        if (!is_array($terms)) {
            return MultilingualList::new();
        }

        $strings = [];
        foreach ($terms as $term) {
            $strings[] = $this->bilingualTermName($term);
        }

        return MultilingualList::new(...$strings);
    }

    final protected function bilingualTermMeta(?WP_Term $term, string $key): MultilingualString
    {
        if (!$term instanceof WP_Term) {
            return MultilingualString::empty();
        }

        return MultilingualString::fromTranslations(
            $this->idGenerator->generateTermMetaId($term, $key),
            (string) get_term_meta($term->term_id, $key, true),
            (string) get_term_meta($term->term_id, self::addEnglishSuffix($key), true),
        );
    }

    final protected function bilingualPostMeta(?WP_Post $post, string $key): MultilingualString
    {
        if (!$post instanceof WP_Post) {
            return MultilingualString::empty();
        }

        return MultilingualString::fromTranslations(
            $this->idGenerator->generatePostMetaId($post, $key),
            (string) get_post_meta($post->ID, $key, true),
            (string) get_post_meta($post->ID, self::addEnglishSuffix($key), true),
        );
    }

    final protected function saveBilingualPostMeta(
        int $postId,
        MultilingualString $multilingualString
    ): void {

        [
            'type' => $type,
            'entityId' => $parsedPostId,
            'subField' => $key,
        ] = $this->idGenerator->parseId($multilingualString->id());

        if ($type !== 'post_meta' || !$key) {
            throw new RuntimeException(
                sprintf(
                    'Could not save multilingual string with id %s as post meta.',
                    $multilingualString->id()
                )
            );
        }

        if ($postId !== (int) $parsedPostId) {
            throw new RuntimeException(
                sprintf(
                    'Could not save multilingual string with id %s as post meta for post %d.',
                    $multilingualString->id(),
                    $postId
                )
            );
        }

        update_post_meta($postId, $key, $multilingualString->inGerman());
        update_post_meta($postId, self::addEnglishSuffix($key), $multilingualString->inEnglish());
    }

    final protected function bilingualOption(string $key): MultilingualString
    {
        $optionKey = self::addOptionPrefix($key);
        $option = (array) get_option($optionKey, []);

        return MultilingualString::fromTranslations(
            $this->idGenerator->generateOptionId($optionKey),
            (string) ($option[MultilingualString::DE] ?? ''),
            (string) ($option[MultilingualString::EN] ?? ''),
        );
    }

    final protected function bilingualLinkFromTerm(?WP_Term $term): MultilingualLink
    {
        return MultilingualLink::new(
            $term instanceof WP_Term ? $this->idGenerator->generateTermId($term) : '',
            name: $this->bilingualTermName($term),
            linkText: $this->bilingualTermMeta($term, MultilingualLink::LINK_TEXT),
            linkUrl: $this->bilingualTermMeta($term, MultilingualLink::LINK_URL),
        );
    }

    final protected function bilingualTermLinks(WP_Post $post, string $taxonomy): MultilingualLinks
    {
        $terms = get_the_terms($post, $taxonomy);
        if (!is_array($terms)) {
            return MultilingualLinks::new();
        }

        $links = [];
        foreach ($terms as $term) {
            $links[] = $this->bilingualLinkFromTerm($term);
        }

        return MultilingualLinks::new(...$links);
    }

    final protected function bilingualLinkFromOption(string $key): MultilingualLink
    {
        $optionKey = self::addOptionPrefix($key);
        // TODO: is it ok to have this settings serialized? Maybe yes.
        $option = (array) get_option($optionKey, []);

        return MultilingualLink::new(
            $this->idGenerator->generateOptionId($optionKey),
            name: MultilingualString::fromTranslations(
                $this->idGenerator->generateOptionId($optionKey, MultilingualLink::NAME),
                (string) ($option[MultilingualLink::NAME] ?? ''),
                (string) ($option[self::addEnglishSuffix(MultilingualLink::NAME)] ?? ''),
            ),
            linkText: MultilingualString::fromTranslations(
                $this->idGenerator->generateOptionId($optionKey, MultilingualLink::LINK_TEXT),
                (string) ($option[MultilingualLink::LINK_TEXT] ?? ''),
                (string) ($option[self::addEnglishSuffix(MultilingualLink::LINK_TEXT)] ?? ''),
            ),
            linkUrl: MultilingualString::fromTranslations(
                $this->idGenerator->generateOptionId($optionKey, MultilingualLink::LINK_URL),
                (string) ($option[MultilingualLink::LINK_URL] ?? ''),
                (string) ($option[self::addEnglishSuffix(MultilingualLink::LINK_URL)] ?? ''),
            ),
        );
    }

    public static function addEnglishSuffix(string $key): string
    {
        return $key . '_' . MultilingualString::EN;
    }

    public static function addOptionPrefix(string $key): string
    {
        return self::OPTION_PREFIX . '_' . $key;
    }
}
