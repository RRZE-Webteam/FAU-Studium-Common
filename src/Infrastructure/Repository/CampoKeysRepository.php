<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Domain\CampoKeys;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AreaOfStudyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\DegreeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\StudyLocationTaxonomy;
use RuntimeException;
use WP_Error;
use WP_Term;

final class CampoKeysRepository
{
    public const TAXONOMY_TO_CAMPO_KEY_MAP = [
        DegreeTaxonomy::KEY => DegreeProgram::DEGREE,
        StudyLocationTaxonomy::KEY => DegreeProgram::LOCATION,
        AreaOfStudyTaxonomy::KEY => DegreeProgram::AREA_OF_STUDY,
    ];

    public const CAMPO_KEY_TERM_META_KEY = 'uniquename';

    private const HIS_CODE_DELIMITER = '|';

    public function degreeProgramCampoKeys(DegreeProgramId $degreeProgramId): CampoKeys
    {
        /** @var WP_Error|array<WP_Term> $terms */
        $terms = wp_get_post_terms(
            $degreeProgramId->asInt(),
            array_keys(self::TAXONOMY_TO_CAMPO_KEY_MAP)
        );

        if ($terms instanceof WP_Error) {
            return CampoKeys::empty();
        }

        $map = [];

        foreach ($terms as $term) {
            $campoKey = (string) get_term_meta($term->term_id, self::CAMPO_KEY_TERM_META_KEY, true);

            if (empty($campoKey)) {
                continue;
            }

            $campoKeyType = self::TAXONOMY_TO_CAMPO_KEY_MAP[$term->taxonomy] ?? null;

            if (is_null($campoKeyType)) {
                continue;
            }

            $map[$campoKeyType][$term->term_id] = $campoKey;
        }

        return CampoKeys::fromArray($map);
    }

    /**
     * Return a map of taxonomy keys to terms based on a given HIS code.
     *
     * @return array<string, int>
     */
    public function taxonomyToTermsMapFromHisCode(string $hisCode): array
    {
        $result = [];

        $campoKeys = $this->campoKeysFromHisCode($hisCode);

        foreach (self::TAXONOMY_TO_CAMPO_KEY_MAP as $taxonomy => $campoKeyType) {
            $campoKey = $campoKeys[$campoKeyType] ?? null;

            if (!is_string($campoKey) || $campoKey === '') {
                continue;
            }

            $term = $this->findTermByCampoKey($taxonomy, $campoKey);
            $result[$taxonomy] = $term instanceof WP_Term ? $term->term_id : 0;
        }

        return $result;
    }

    private function findTermByCampoKey(string $taxonomy, string $campoKey): ?WP_Term
    {
        if ($campoKey === '') {
            return null;
        }

        /** @var WP_Error|array<WP_Term> $terms */
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'meta_key' => self::CAMPO_KEY_TERM_META_KEY,
            'meta_value' => $campoKey,
        ]);

        if ($terms instanceof WP_Error) {
            return null;
        }

        return $terms[0] ?? null;
    }

    public function campoKeysFromHisCode(string $hisCode): array
    {
        $parts = explode(self::HIS_CODE_DELIMITER, $hisCode);
        $map = [
            DegreeProgram::DEGREE => $parts[0] ?? null,
            DegreeProgram::AREA_OF_STUDY => $parts[1] ?? null,
            DegreeProgram::LOCATION => $parts[6] ?? null,
        ];

        return array_filter($map, fn($value) => !is_null($value));
    }
}
