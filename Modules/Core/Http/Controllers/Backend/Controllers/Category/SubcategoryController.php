<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Category;

use App\Config\ps_constant;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Category\SubcategoryServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Category\Subcategory;
use App\Http\Controllers\PsController;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\SubcategoryLanguageString;
use Modules\Core\Http\Requests\Category\UpdateSubcategoryRequest;
use Modules\Core\Http\Requests\Category\StoreSubcategoryRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Transformers\Backend\Model\Category\CategoryWithKeyResource;
use Modules\Core\Transformers\Backend\Model\Category\SubcategoryWithKeyResource;

class SubcategoryController extends PsController
{
    private const parentPath = 'subcategory';

    private const indexPath = self::parentPath . '/Index';

    private const createPath = self::parentPath . '/Create';

    private const editPath = self::parentPath . '/Edit';

    private const indexRoute = self::parentPath . '.index';

    private const createRoute = self::parentPath . '.create';

    private const editRoute = self::parentPath . '.edit';

    private const imageKey = 'sub_cat_photo';

    private const iconKey = 'sub_cat_icon';

    public function __construct(
        protected SubcategoryServiceInterface $subcategoryService,
        protected CategoryServiceInterface $categoryService,
        protected LanguageServiceInterface $languageService,
        protected CoreFieldServiceInterface $coreFieldService
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Subcategory::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(Subcategory::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreSubcategoryRequest $request)
    {
        try {

            // Validate the request data
            $validData = $request->validated();
            // Get Image File
            $cover = $request->file(self::imageKey);

            // Get Icon File ico
            $icon = $request->file(self::iconKey);

            // Save Subcategory
            $this->subcategoryService->save(
                subcategoryData: $validData,
                subcategoryImage: $cover,
                subcategoryIcon: $icon
            );

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        $subcategory = $this->subcategoryService->get($id);
        // check permission
        $this->handlePermissionWithModel($subcategory, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateSubcategoryRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            // Get Image File
            $cover = $request->file(self::imageKey);

            // Get Icon File
            $icon = $request->file(self::iconKey);

            $this->subcategoryService->update(
                id: $id,
                subcategoryData: $validatedData,
                subcategoryImageId: $request->input('cover_id'),
                subcategoryImage: $cover,
                subcategoryIconId: $request->input('icon_id'),
                subcategoryIcon: $icon
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $subcategory = $this->subcategoryService->get($id);

            $this->handlePermissionWithModel($subcategory, Constants::deleteAbility);

            $dataArr = $this->subcategoryService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {
            $subcategory = $this->subcategoryService->get($id);

            $this->handlePermissionWithModel($subcategory, Constants::editAbility);

            $status = $this->prepareStatusData($subcategory);

            $this->subcategoryService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function importCSV(Request $request)
    {

        try {
            $subcategoryData = $request->file(Constants::csvFile);

            $this->subcategoryService->importCSVFile($subcategoryData);

            return redirectView(self::indexRoute);
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
    private function prepareIndexData($request)
    {
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'category_id' => $request->input('category_filter') === 'all' ? null : $request->input('category_filter'),
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'page' => $request->input('page') ?? null
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // current language
        // $langConds = $this->prepareLanguageData();
        // $activeLanguage = $this->languageService->getAll(conds: $langConds);

        // manipulate subcategory data
        $relation = ['owner', 'editor', 'category.categoryLanguageString'];

        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);

        $subcategories = SubcategoryWithKeyResource::collection($this->subcategoryService->getAll(
            relation: $relation,
            languageId: $language->id,
            status: null,
            limit: null,
            offset: null,
            conds: $conds,
            noPagination: false,
            pagPerPage: $row
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::subcategory, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createSubcategory' => 'create-subCategory',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'subcategories' => $subcategories,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'selectedCategory' => $conds['category_id'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareCreateData()
    {
        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);

        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll(
            languageId: $language->id,
            relation: null,
            status: Constants::publish,
            noPagination: Constants::yes,
            conds: [
                'order_by' => Category::ordering,
                'order_type' => ps_constant::ascending
            ]
        ));

        return [
            'categories' => $categories,
        ];
    }

    private function prepareEditData($id)
    {
        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);

        $dataWithRelation = ['category', 'cover', 'icon'];
        $subcategory = $this->subcategoryService->get($id, null, $language->id, $dataWithRelation);

        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll(
            relation: null,
            status: Constants::publish,
            languageId: $language->id
        ));

        $conds = [
            'module_name' => Constants::subcategory,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $languages = $this->languageService->getAll();
        $subCategoryLanguages = SubcategoryLanguageString::where(SubcategoryLanguageString::subcategoryId, $id)->get();

        // for existing category
        if ($subCategoryLanguages->count() == 0) {
            foreach ($languages as $language) {
                $subCategoryLanguages[] = [
                    'language_id' => $language->id,
                    'value' => __($subcategory->name),
                ];
            }
        }

        $validation = [];
        foreach ($core_headers as $core_header) {
            if ($core_header->field_name == 'sub_cat_photo') {
                array_push($validation, 'cover');
            }
            if ($core_header->field_name == 'sub_cat_icon') {
                array_push($validation, 'icon');
            }
        }

        return [
            'subcategory' => $subcategory,
            'categories' => $categories,
            'validation' => $validation,
            'languages' => $languages,
            'subcategoryLanguages' => $subCategoryLanguages,
        ];
    }

    private function prepareStatusData($subcategory)
    {
        return $subcategory->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareLanguageData()
    {
        return ['symbol' => Session::get('applocale') ?? 'en'];
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
