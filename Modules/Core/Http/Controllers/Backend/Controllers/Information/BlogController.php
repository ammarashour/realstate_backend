<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Information;

use App\Config\ps_constant;
use App\Http\Contracts\Blog\BlogServiceInterface;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use Illuminate\Http\Request;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Http\Requests\Information\StoreBlogRequest;
use Modules\Core\Http\Requests\Information\UpdateBlogRequest;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use App\Http\Controllers\PsController;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Transformers\Backend\Model\Information\BlogWithKeyResource;

class BlogController extends PsController
{
    private const parentPath = 'blog';

    private const indexPath = self::parentPath . '/Index';

    private const createPath = self::parentPath . '/Create';

    private const editPath = self::parentPath . '/Edit';

    private const indexRoute = self::parentPath . '.index';

    private const createRoute = self::parentPath . '.create';

    private const editRoute = self::parentPath . '.edit';

    private const imageKey = 'cover';

    public function __construct(
        protected BlogServiceInterface $blogService,
        protected CoreFieldFilterSettingService $coreFieldFilterSettingService,
        protected LocationCityServiceInterface $locationCityService
    ) {

        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Blog::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);
        /** @todo SEO */
        $dataArr['seot'] = "SEO : Title";
        $dataArr['seod'] = "SEO : Description";
        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(Blog::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreBlogRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Get Image File
            $file = $request->file(self::imageKey);

            // Save Blog
            $this->blogService->save(
                blogData: $validData,
                blogImage: $file
            );

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $blog = $this->blogService->get($id);
        $this->handlePermissionWithModel($blog, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            // Get Image File
            $file = $request->file(self::imageKey);

            $this->blogService->update(
                id: $id,
                blogData: $validatedData,
                blogImageId: $request->input('cover_id'),
                blogImage: $file
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $blog = $this->blogService->get($id);

            $this->handlePermissionWithModel($blog, Constants::deleteAbility);

            $dataArr = $this->blogService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $blog = $this->blogService->get($id);

            $this->handlePermissionWithModel($blog, Constants::editAbility);

            $status = $this->prepareStatusData($blog);

            $this->blogService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function screenDisplayUiStore(Request $request)
    {
        makeColumnHideShown($request);

        return redirect()->back();
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------
    private function prepareCreateData()
    {
        $cities = $this->locationCityService->getAll(status: Constants::publish, noPagination: Constants::yes);
        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getCoreFields(withNoPag: 1, moduleName: Constants::blog);

        return [
            'cities' => $cities,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];
    }

    private function prepareIndexData($request)
    {
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'location_city_id' => $request->input('city_filter') === 'all' ? null : $request->input('city_filter'),
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate blog data
        $relations = ['city', 'owner', 'editor'];
        $blogs = BlogWithKeyResource::collection($this->blogService->getAll(
            relation: $relations,
            noPagination: false,
            pagPerPage: $row,
            conds: $conds
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::blog, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createBlog' => 'create-blog',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'blogs' => $blogs,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareEditData($id)
    {

        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getCoreFields(withNoPag: 1, moduleName: Constants::blog);
        $dataWithRelation = ['cover', 'city'];
        $blog = $this->blogService->get($id, $dataWithRelation);

        $cities = $this->locationCityService->getAll(status: Constants::publish, noPagination: Constants::yes);

        /**
         * @todo need to use service
         */
        $conds = [
            'module_name' => Constants::blog,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = CoreField::where($conds)->get();

        $validation = [];
        foreach ($core_headers as $core_header) {
            if ($core_header->field_name === 'blog_photo') {
                array_push($validation, 'cover');
            }
        }

        return [
            'blog' => $blog,
            'cities' => $cities,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'validation' => $validation,
        ];
    }

    private function prepareStatusData($blog)
    {
        return $blog->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), 'action', 'Action', false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        return $controlFieldArr;
    }
}
