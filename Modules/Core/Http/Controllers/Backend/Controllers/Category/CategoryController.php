<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Category;

use App\Config\ps_constant;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Localization\CategoryLanguageString;
use Modules\Core\Entities\Localization\Language;
use App\Http\Controllers\PsController;
use Exception;
use Modules\Core\Http\Requests\Category\StoreCategoryRequest;
use Modules\Core\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use Modules\Core\Transformers\Backend\Model\Category\CategoryWithKeyResource;

class CategoryController extends PsController
{
    private const parentPath = 'category';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    private const imageKey = 'cat_photo';

    private const iconKey = 'cat_icon';

    public function __construct(protected CategoryServiceInterface $categoryService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected LanguageServiceInterface $languageService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Category::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(Category::class, Constants::createAbility);

        return renderView(self::createPath);
    }

    public function store(StoreCategoryRequest $request)
    {
        try {

            // Validate the request data
            $validData = $request->validated();
            // Get Image File
            $cover = $request->file(self::imageKey);

            // Get Icon File ico
            $icon = $request->file(self::iconKey);

            // Save Category
            $this->categoryService->save(categoryData : $validData,
                categoryImage : $cover,
                categoryIcon : $icon);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }

    }

    public function edit($id)
    {
        $category = $this->categoryService->get($id);
        // check permission
        $this->handlePermissionWithModel($category, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        try {

            $validatedData = $request->validated();

            // Get Image File
            $cover = $request->file(self::imageKey);

            // Get Icon File
            $icon = $request->file(self::iconKey);

            $this->categoryService->update(id : $id,
                categoryData : $validatedData,
                categoryImageId : $request->input('cover_id'),
                categoryImage : $cover,
                categoryIconId : $request->input('icon_id'),
                categoryIcon : $icon);

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $category = $this->categoryService->get($id);

            $this->handlePermissionWithModel($category, Constants::deleteAbility);

            $dataArr = $this->categoryService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {
            $category = $this->categoryService->get($id);

            $this->handlePermissionWithModel($category, Constants::editAbility);

            $status = $this->prepareStatusData($category);

            $this->categoryService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function importCSV(Request $request)
    {

        try {
            $categoryData = $request->file(Constants::csvFile);

            $this->categoryService->importCSVFile($categoryData);

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
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'page' => $request->input('page') ?? null
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate category data
        $relation = ['owner', 'editor'];

        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);

        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll(relation : $relation,
            status : null,
            languageId: $language->id,
            limit : null,
            offset : null,
            conds : $conds,
            noPagination : false,
            pagPerPage: $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::category, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createCategory' => 'create-category',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'categories' => $categories,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareEditData($id)
    {
        $dataWithRelation = ['cover', 'icon'];

        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);

        $category = $this->categoryService->get($id, $dataWithRelation, $language->id);

        $conds = [
            'module_name' => Constants::category,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = $this->coreFieldService->getAll(withNoPag: true, conds: $conds);

        $languages = Language::get();
        $categoryLanguages = CategoryLanguageString::where(CategoryLanguageString::categoryId, $id)->get();

        // for existing category
        if ($categoryLanguages->count() == 0) {
            foreach ($languages as $language) {
                $categoryLanguages[] = [
                    'language_id' => $language->id,
                    'value' => __($category->name),
                ];
            }
        }

        $validation = [];
        foreach ($core_headers as $core_header) {
            if ($core_header->field_name == 'cat_photo') {
                array_push($validation, 'cover');
            }
            if ($core_header->field_name == 'cat_icon') {
                array_push($validation, 'icon');
            }
        }

        return [
            'category' => $category,
            'languages' => $languages,
            'categoryLanguages' => $categoryLanguages,
            'validation' => $validation,
        ];

    }

    private function prepareStatusData($category)
    {
        return $category->status == Constants::publish
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
