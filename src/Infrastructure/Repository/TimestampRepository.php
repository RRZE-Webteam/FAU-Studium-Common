<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use DateTimeImmutable;
use DateTimeInterface;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;

final class TimestampRepository
{
    private const MODIFIED_META_KEY = 'degree_program_modified';

    public function created(DegreeProgramId $id): ?DateTimeInterface
    {
        $postDateTime = get_post_datetime($id->asInt());

        return $postDateTime instanceof DateTimeInterface ? $postDateTime : null;
    }

    /**
     * The custom field is updated even if related settings or terms are updated.
     * If the custom field is missing, we can use the native
     * WordPress "post modified" property as a fallback.
     */
    public function modified(DegreeProgramId $id): ?DateTimeInterface
    {
        $timestamp = (int) get_post_meta($id->asInt(), self::MODIFIED_META_KEY, true);

        if ($timestamp < 1) {
            $timestamp = get_post_timestamp($id->asInt(), 'modified');
        }

        if (!is_int($timestamp)) {
            return null;
        }

        $dateTime = new DateTimeImmutable();
        $dateTime = $dateTime->setTimestamp($timestamp);

        return $dateTime->setTimezone(wp_timezone());
    }

    public function updateModified(DegreeProgramId $id): void
    {
        update_post_meta($id->asInt(), self::MODIFIED_META_KEY, time());
    }
}
