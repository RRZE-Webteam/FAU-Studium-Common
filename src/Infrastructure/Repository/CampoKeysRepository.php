<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Domain\CampoKeys;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\AreaOfStudyTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\DegreeTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\StudyLocationTaxonomy;
use WP_Error;
use WP_Term;

final class CampoKeysRepository
{
    public const CAMPO_KEYS_TOTAXONOMY_MAP = [
        DegreeTaxonomy::KEY => DegreeProgram::DEGREE,
        StudyLocationTaxonomy::KEY => DegreeProgram::LOCATION,
        AreaOfStudyTaxonomy::KEY => DegreeProgram::AREA_OF_STUDY,
    ];

    public const CAMPOKEY_TERM_META_KEY = 'uniquename';

    public function degreeProgramCampoKeys(DegreeProgramId $degreeProgramId): CampoKeys
    {
        $campoKeys = CampoKeys::empty();

        /** @var WP_Error|array<WP_Term> $terms */
        $terms = wp_get_post_terms(
            $degreeProgramId->asInt(),
            array_keys(self::CAMPO_KEYS_TOTAXONOMY_MAP)
        );

        if ($terms instanceof WP_Error) {
            return $campoKeys;
        }

        foreach ($terms as $term) {
            $campoKey = (string) get_term_meta($term->term_id, self::CAMPOKEY_TERM_META_KEY, true);

            if (empty($campoKey)) {
                continue;
            }

            $campoKeyType = self::CAMPO_KEYS_TOTAXONOMY_MAP[$term->taxonomy] ?? null;

            if (is_null($campoKeyType)) {
                continue;
            }

            $campoKeys->set($campoKeyType, $campoKey);
        }

        return $campoKeys;
    }

    /**
     * Return a map of taxonomy keys to terms based on a given HIS code.
     *
     * @return array<string, int>
     */
    public function taxonomyToTermsMapFromCampoKeys(CampoKeys $campoKeys): array
    {
        $result = [];

        $campoKeys = $campoKeys->asArray();

        foreach (self::CAMPO_KEYS_TOTAXONOMY_MAP as $taxonomy => $campoKeyType) {
            $campoKey = $campoKeys[$campoKeyType] ?? '';

            if ($campoKey === '') {
                continue;
            }

            $term = $this->findTermByCampoKey($taxonomy, $campoKey);
            $result[$taxonomy] = ! is_null($term) ? $term->term_id : 0;
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
            'meta_key' => self::CAMPOKEY_TERM_META_KEY,
            'meta_value' => $campoKey,
        ]);

        if ($terms instanceof WP_Error) {
            return null;
        }

        return $terms[0] ?? null;
    }
}