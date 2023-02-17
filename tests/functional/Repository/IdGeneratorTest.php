<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Tests\Repository;

use Fau\DegreeProgram\Common\Domain\Degree;
use Fau\DegreeProgram\Common\Domain\MultilingualLink;
use Fau\DegreeProgram\Common\Domain\MultilingualLinks;
use Fau\DegreeProgram\Common\Domain\MultilingualList;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Domain\NumberOfStudents;
use Fau\DegreeProgram\Common\Infrastructure\Repository\IdGenerator;
use Fau\DegreeProgram\Common\Tests\WpDbLess\WpDbLessTestCase;
use RuntimeException;
use WP_Post;
use WP_Term;

final class IdGeneratorTest extends WpDbLessTestCase
{
    public function testGenerateIdsForTerm(): void
    {
        $term = new WP_Term((object) ['term_id' => 5]);
        $sut = new IdGenerator();

        $this->assertSame(
            'term:5',
            $sut->generateTermId($term)
        );
        $this->assertSame(
            'term:5:name',
            $sut->generateTermId($term, 'name')
        );
        $this->assertSame(
            'term_meta:5:abbreviation',
            $sut->generateTermMetaId($term, 'abbreviation')
        );
    }

    public function testGenerateIdsForPost(): void
    {
        $post = new WP_Post((object) ['ID' => 5]);
        $sut = new IdGenerator();

        $this->assertSame(
            'post:5',
            $sut->generatePostId($post)
        );
        $this->assertSame(
            'post:5:title',
            $sut->generatePostId($post, 'title')
        );
        $this->assertSame(
            'post_meta:5:meta_description',
            $sut->generatePostMetaId($post, 'meta_description')
        );
    }

    public function testGenerateIdsForOption(): void
    {
        $optionKey = 'fau_start_of_semester';
        $sut = new IdGenerator();

        $this->assertSame(
            'option:fau_start_of_semester',
            $sut->generateOptionId($optionKey)
        );
        $this->assertSame(
            'option:fau_start_of_semester:link_text',
            $sut->generateOptionId($optionKey, 'link_text')
        );
    }

    public function testParseId(): void
    {
        $sut = new IdGenerator();
        [
            'type' => $type,
            'entityId' => $parsedPostId,
            'subField' => $key,
        ] = $sut->parseId('post:5');
        $this->assertSame('post', $type);
        $this->assertSame('5', $parsedPostId);
        $this->assertNull($key);

        [
            'type' => $type,
            'entityId' => $parsedPostId,
            'subField' => $key,
        ] = $sut->parseId('post_meta:5:meta_description');
        $this->assertSame('post_meta', $type);
        $this->assertSame('5', $parsedPostId);
        $this->assertSame('meta_description', $key);
    }

    /**
     * @dataProvider termListIdsProvider
     */
    public function testTermListIds(
        MultilingualString|MultilingualList|MultilingualLink|MultilingualLinks|NumberOfStudents|Degree $valueObject,
        array $ids
    ): void {

        $sut = new IdGenerator();
        $this->assertSame($ids, $sut->termIdsList($valueObject));
    }

    public function termListIdsProvider(): iterable
    {
        yield 'multilingual_string' => [
                MultilingualString::fromTranslations('term:25:name', '', ''),
                [25],
            ];

        yield 'multilingual_list' => [
            MultilingualList::new(
                MultilingualString::fromTranslations('term:25:name', '', ''),
                MultilingualString::fromTranslations('term:26:name', '', ''),
            ),
            [25, 26],
        ];

        yield 'multilingual_link' => [
            MultilingualLink::new(
                'term:25',
                MultilingualString::fromTranslations('term:25:name', '', ''),
                MultilingualString::fromTranslations('term_meta:25:link_text', '', ''),
                MultilingualString::fromTranslations('term_meta:25:link_url', '', ''),
            ),
            [25],
        ];

        yield 'multilingual_links' => [
            MultilingualLinks::new(
                MultilingualLink::new(
                    'term:25',
                    MultilingualString::fromTranslations('term:25:name', '', ''),
                    MultilingualString::fromTranslations('term_meta:25:link_text', '', ''),
                    MultilingualString::fromTranslations('term_meta:25:link_url', '', ''),
                ),
                MultilingualLink::new(
                    'term:27',
                    MultilingualString::fromTranslations('term:27:name', '', ''),
                    MultilingualString::fromTranslations('term_meta:27:link_text', '', ''),
                    MultilingualString::fromTranslations('term_meta:27:link_url', '', ''),
                ),
            ),
            [25, 27],
        ];

        yield 'number_of_students' => [
            NumberOfStudents::new('term:25', ''),
            [25],
        ];

        yield 'degree' => [
            Degree::new(
                'term:25',
                MultilingualString::fromTranslations('term:25:name', '', ''),
                MultilingualString::fromTranslations('term_meta:25:name', '', ''),
                null
            ),
            [25],
        ];
    }

    public function testTermListIdsForPostMeta(): void
    {
        $this->expectException(RuntimeException::class);
        $sut = new IdGenerator();
        $sut->termIdsList(
            MultilingualString::fromTranslations('post_meta:25:description', '', '')
        );
    }
}
