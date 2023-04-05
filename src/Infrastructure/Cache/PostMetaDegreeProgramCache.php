<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Cache;

use DateInterval;
use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use JsonException;
use Psr\SimpleCache\CacheInterface;

final class PostMetaDegreeProgramCache implements CacheInterface
{
    public function __construct(
        private CacheKeyGenerator $cacheKeyGenerator,
    ) {
    }

    public function get(string $key, mixed $default = null): ?array
    {
        [$type, $postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);

        $meta = get_post_meta($postId, self::postMetaKey($type), true);
        if (!$meta) {
            return null;
        }

        try {
            return (array) json_decode((string) $meta, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        [$type, $postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);
        $metaKey = self::postMetaKey($type);

        try {
            // `update_metadata` returns false if value was not changed.
            // We need to handle this edge case separately.
            $encodedValue = json_encode($value, JSON_THROW_ON_ERROR);
            $existedValue = get_post_meta($postId, $metaKey, true);
            if ($encodedValue === $existedValue) {
                return true;
            }

            // Add slashes to prevent broken JSON encoded value
            // because `update_metadata` uses wp_unslash under the hood.
            // `update_metadata` is used to allow adding post meta to revisions.
            return (bool) update_metadata('post', $postId, $metaKey, addslashes($encodedValue));
        } catch (JsonException) {
            return false;
        }
    }

    public function delete(string $key): bool
    {
        if (!$this->has($key)) {
            // To prevent wrong result if meta doesn't exist
            return true;
        }

        [$type, $postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);

        return delete_post_meta($postId, self::postMetaKey($type));
    }

    public function clear(): bool
    {
        /** @var array<int> $ids */
        $ids = get_posts([
            'numberposts' => -1,
            'post_type' => DegreeProgramPostType::KEY,
            'post_status' => 'any',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => self::postMetaKey(CacheKeyGenerator::RAW_TYPE),
                    'compare' => 'EXISTS',
                ],
                [
                    'key' => self::postMetaKey(CacheKeyGenerator::TRANSLATED_TYPE),
                    'compare' => 'EXISTS',
                ],
            ],
            'fields' => 'ids',
        ]);

        $result = [];
        foreach ($ids as $id) {
            $result[] = delete_post_meta(
                $id,
                self::postMetaKey(CacheKeyGenerator::RAW_TYPE)
            );
            $result[] = delete_post_meta(
                $id,
                self::postMetaKey(CacheKeyGenerator::TRANSLATED_TYPE)
            );
        }

        return !in_array(false, $result, true);
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }

        return $result;
    }

    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        $result = [];
        foreach ($values as $key => $value) {
            $result[] = $this->set((string) $key, $value);
        }

        return !in_array(false, $result, true);
    }

    public function deleteMultiple(iterable $keys): bool
    {
        $result = [];
        foreach ($keys as $key) {
            $result[] = $this->delete($key);
        }

        return !in_array(false, $result, true);
    }

    public function has(string $key): bool
    {
        [$type, $postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);

        return metadata_exists('post', $postId, self::postMetaKey($type));
    }

    public static function postMetaKey(string $type): string
    {
        return 'fau_cache_degree_program_' . $type;
    }
}
