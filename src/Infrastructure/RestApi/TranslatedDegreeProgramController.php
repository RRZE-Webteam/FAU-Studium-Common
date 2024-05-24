<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\RestApi;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Filter\AdmissionRequirementTypeFilter;
use Fau\DegreeProgram\Common\Application\Filter\AreaOfStudyFilter;
use Fau\DegreeProgram\Common\Application\Filter\AttributeFilter;
use Fau\DegreeProgram\Common\Application\Filter\DegreeFilter;
use Fau\DegreeProgram\Common\Application\Filter\FacultyFilter;
use Fau\DegreeProgram\Common\Application\Filter\GermanLanguageSkillsForInternationalStudentsFilter;
use Fau\DegreeProgram\Common\Application\Filter\SearchKeywordFilter;
use Fau\DegreeProgram\Common\Application\Filter\SemesterFilter;
use Fau\DegreeProgram\Common\Application\Filter\StudyLocationFilter;
use Fau\DegreeProgram\Common\Application\Filter\SubjectGroupFilter;
use Fau\DegreeProgram\Common\Application\Filter\TeachingLanguageFilter;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use WP_Error;
use WP_Post;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * phpcs:disable Inpsyde.CodeQuality.VariablesName.SnakeCaseVar
 * phpcs:disable Inpsyde.CodeQuality.NoAccessors.NoGetter
 * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
final class TranslatedDegreeProgramController extends WP_REST_Controller
{
    /**
     * @var string
     */
    protected $namespace = 'fau/v1';

    /**
     * @var string
     */
    protected $rest_base = 'degree-program';

    /**
     * @var array|null
     */
    protected $schema;

    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private DegreeProgramCollectionRepository $degreeProgramCollectionRepository,
    ) {
    }

    public function register_routes(): void
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_items'],
                'permission_callback' => [$this, 'get_items_permissions_check'],
                'args' => $this->get_collection_params(),
            ],
        ]);
        register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', [
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'get_item'],
                'permission_callback' => [$this, 'get_item_permissions_check'],
                'args' => [
                    'lang' => self::languageParam(),
                ],
            ],
        ]);
        register_rest_route($this->namespace, $this->rest_base . '/schema', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_public_item_schema'],
            'permission_callback' => static fn() => true,
        ]);
    }

    /**
     * @param WP_REST_Request $request Full data about the request.
     *
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function get_items($request): WP_Error|WP_REST_Response
    {
        $criteria = CollectionCriteria::new()
            ->withPage((int) $request->get_param('page'))
            ->withPerPage((int) $request->get_param('per_page'))
            ->addFilter(
                new SearchKeywordFilter(
                    (string) $request->get_param('search')
                ),
                AdmissionRequirementTypeFilter::fromInput((array) $request->get_param(AdmissionRequirementTypeFilter::KEY)),
                AreaOfStudyFilter::fromInput((array) $request->get_param(AreaOfStudyFilter::KEY)),
                AttributeFilter::fromInput((array) $request->get_param(AttributeFilter::KEY)),
                DegreeFilter::fromInput((array) $request->get_param(DegreeFilter::KEY)),
                FacultyFilter::fromInput((array) $request->get_param(FacultyFilter::KEY)),
                SemesterFilter::fromInput((array) $request->get_param(SemesterFilter::KEY)),
                StudyLocationFilter::fromInput((array) $request->get_param(StudyLocationFilter::KEY)),
                SubjectGroupFilter::fromInput((array) $request->get_param(SubjectGroupFilter::KEY)),
                TeachingLanguageFilter::fromInput((array) $request->get_param(TeachingLanguageFilter::KEY)),
                GermanLanguageSkillsForInternationalStudentsFilter::fromInput((array) $request->get_param(GermanLanguageSkillsForInternationalStudentsFilter::KEY))
            )
            ->withOrderBy([
                (string) $request->get_param('order_by') =>
                    (string) $request->get_param('order') === 'asc' ? 'asc' : 'desc',
            ]);

        $views = $this->degreeProgramCollectionRepository->findTranslatedCollection(
            $criteria,
            $this->requestedLanguage($request)
        );

        if (!$views instanceof PaginationAwareCollection) {
            return new WP_Error(
                'unexpected_error',
                _x(
                    'Something went wrong. Please try again later.',
                    'rest_api: response status',
                    'fau-degree-program'
                ),
                ['status' => 500]
            );
        }

        $data = [];
        foreach ($views as $view) {
            $data[] = $this->prepare_response_for_collection(
                new WP_REST_Response($view->asArray())
            );
        }

        $response = new WP_REST_Response($data);
        if ($views->totalItems() > 0 && $views->currentPage() > $views->maxPages()) {
            return new WP_Error(
                'rest_post_invalid_page_number',
                _x(
                    'The page number requested is larger than the number of pages available.',
                    'rest_api: response status',
                    'fau-degree-program'
                ),
                ['status' => 400]
            );
        }
        $response->header('X-WP-Total', (string) $views->totalItems());
        $response->header('X-WP-TotalPages', (string) $views->maxPages());

        $collectionUrl = rest_url($this->namespace . '/' . $this->rest_base);
        $base = add_query_arg(urlencode_deep($request->get_query_params()), $collectionUrl);

        if ($views->previousPage() !== null) {
            $response->link_header(
                'prev',
                add_query_arg('page', $views->previousPage(), $base)
            );
        }

        if ($views->nextPage() !== null) {
            $response->link_header(
                'next',
                add_query_arg('page', $views->nextPage(), $base)
            );
        }

        return $response;
    }

    /**
     * @param WP_REST_Request $request Full data about the request.
     */
    public function get_item($request): WP_Error|WP_REST_Response
    {
        /** @var WP_Post $post */
        $post = get_post((int)$request->get_param('id'));
        $view = $this->prepare_item_for_response(
            $post,
            $request
        );

        if ($view instanceof DegreeProgramViewTranslated) {
            return new WP_REST_Response($view->asArray());
        }

        return new WP_Error(
            'not_found',
            _x(
                'Degree program not found.',
                'rest_api: response status',
                'fau-degree-program'
            ),
            ['status' => 404]
        );
    }

    public function get_items_permissions_check($request): bool
    {
        return true;
    }

    public function get_item_permissions_check($request): bool|WP_Error
    {
        $id = (int)$request->get_param('id');

        $error = new WP_Error(
            'not_found',
            _x(
                'Degree program not found.',
                'rest_api: response status',
                'fau-degree-program'
            ),
            ['status' => 404]
        );

        if ($id <= 0) {
            return $error;
        }

        $post = get_post($id);
        if (!$post instanceof WP_Post) {
            return $error;
        }

        if (
            $post->post_type !== DegreeProgramPostType::KEY
            || $post->post_status !== 'publish'
        ) {
            return $error;
        }

        return true;
    }

    /**
     * @param WP_Post $item Post.
     * @param WP_REST_Request $request Request object.
     */
    public function prepare_item_for_response($item, $request): DegreeProgramViewTranslated|null
    {
        return $this->degreeProgramViewRepository->findTranslated(
            DegreeProgramId::fromInt($item->ID),
            $this->requestedLanguage($request),
        );
    }

    /**
     * @psalm-return 'de' | 'en'
     */
    private function requestedLanguage(WP_REST_Request $request): string
    {
        $languageCode = (string) ($request->get_param('lang') ?? MultilingualString::DE);

        return in_array($languageCode, [MultilingualString::DE, MultilingualString::EN], true)
            ? $languageCode
            : MultilingualString::DE;
    }

    /**
     * @psalm-return array<string, mixed>
     */
    public function get_collection_params(): array
    {
        [
            'page' => $page,
            'per_page' => $perPage,
            'search' => $search,
        ] = parent::get_collection_params();

        return [
            'page' => $page,
            'per_page' => $perPage,
            'search' => $search,
            'lang' => self::languageParam(),
        ];
    }

    private static function languageParam(): array
    {
        return [
            'description' => _x(
                'Language code ("de" and "en" are supported).',
                'rest_api: schema item description',
                'fau-degree-program'
            ),
            'type' => 'string',
            'default' => MultilingualString::DE,
        ];
    }

    /**
     * phpcs:disable Inpsyde.CodeQuality.FunctionLength.TooLong
     */
    public function get_item_schema(): array
    {
        if (isset($this->schema)) {
            return $this->schema;
        }

        $this->schema = [
            DegreeProgram::ID => [
                'description' => _x(
                    'Unique identifier for the degree program.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'integer',
            ],
            DegreeProgram::FEATURED_IMAGE => [
                'description' => _x(
                    'Feature image.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::TEASER_IMAGE => [
                'description' => _x(
                    'Teaser image.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::TITLE => [
                'description' => _x(
                    'Title.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::SUBTITLE =>  [
                'description' => _x(
                    'Subtitle.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::STANDARD_DURATION => [
                'description' => _x(
                    'Standard duration of study.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::FEE_REQUIRED => [
                'description' => _x(
                    'Fee required.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'boolean',
            ],
            DegreeProgram::START => [
                'description' => _x(
                    'Start of degree program.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgram::NUMBER_OF_STUDENTS => [
                'description' => _x(
                    'Number of students.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::TEACHING_LANGUAGE => [
                'description' => _x(
                    'Teaching language.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::ATTRIBUTES => [
                'description' => _x(
                    'Attributes.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgram::DEGREE => [
                'description' => _x(
                    'Degree.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::FACULTY => [
                'description' => _x(
                    'Faculty.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgram::LOCATION => [
                'description' => _x(
                    'Study location.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgram::SUBJECT_GROUPS => [
                'description' => _x(
                    'Subject groups.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgram::VIDEOS => [
                'description' => _x(
                    'Videos.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgram::META_DESCRIPTION => [
                'description' => _x(
                    'Meta description.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::CONTENT => [
                'description' => _x(
                    'Content.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgramViewTranslated::ADMISSION_REQUIREMENT_LINK => [
                'description' => _x(
                    'Admission requirement link.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::ADMISSION_REQUIREMENTS => [
                'description' => _x(
                    'Admission requirements.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS =>  [
                'description' => _x(
                    'Content-related admission requirements for Master’s degree.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER =>  [
                'description' => _x(
                    'Application deadline winter semester.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER =>  [
                'description' => _x(
                    'Application deadline summer semester.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::DETAILS_AND_NOTES =>  [
                'description' => _x(
                    'Details and notes.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::LANGUAGE_SKILLS =>  [
                'description' => _x(
                    'Language skills.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY =>  [
                'description' => _x(
                    'Language skills for Faculty of Humanities, Social Sciences, and Theology only.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS =>
                [
                    'description' => _x(
                        'Language certificates/German language skills for international applicants.',
                        'rest_api: schema item description',
                        'fau-degree-program'
                    ),
                    'type' => 'object',
                ],
            DegreeProgram::START_OF_SEMESTER =>  [
                'description' => _x(
                    'Start of semester.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::SEMESTER_DATES =>  [
                'description' => _x(
                    'Semester dates.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::EXAMINATIONS_OFFICE =>  [
                'description' => _x(
                    'Examinations Office.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::EXAMINATION_REGULATIONS =>  [
                'description' => _x(
                    'Degree program and examination regulations.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::MODULE_HANDBOOK => [
                'description' => _x(
                    'Module handbook.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::URL => [
                'description' => _x(
                    'Degree program URL.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::DEPARTMENT => [
                'description' => _x(
                    'Department.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::STUDENT_ADVICE => [
                'description' => _x(
                    'Student Advice and Career Service.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => [
                'description' => _x(
                    'Subject-specific advice.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::SERVICE_CENTERS => [
                'description' => _x(
                    'Counseling and Service Centers at FAU.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::INFO_BROCHURE => [
                'description' => _x(
                    'Info brochure degree program.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::SEMESTER_FEE => [
                'description' => _x(
                    'Semester fee.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::DEGREE_PROGRAM_FEES => [
                'description' => _x(
                    'Degree program fees.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'string',
            ],
            DegreeProgram::ABROAD_OPPORTUNITIES => [
                'description' => _x(
                    'Opportunities for spending time abroad.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::COMBINATIONS => [
                'description' => _x(
                    'Degree program possible combinations.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgram::LIMITED_COMBINATIONS => [
                'description' => _x(
                    'Degree program limited possible combinations.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'array',
            ],
            DegreeProgramViewTranslated::TRANSLATIONS => [
                'description' => _x(
                    'Available translations.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS => [
                'description' => _x(
                    'Notes for international applicants.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::STUDENT_INITIATIVES => [
                'description' => _x(
                    'Students\' Union/Student Initiatives.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
            DegreeProgram::CAMPO_KEYS => [
                'description' => _x(
                    'Degree program Campo Keys.',
                    'rest_api: schema item description',
                    'fau-degree-program'
                ),
                'type' => 'object',
            ],
        ];

        return $this->schema;
    }
}
