<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use App\Config\ps_constant;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Category\SubcategoryServiceInterface;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Configuration\SettingServiceInterface;
use App\Http\Contracts\Configuration\SystemConfigServiceInterface;
use App\Http\Contracts\Financial\ItemCurrencyServiceInterface;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Item\ItemServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use App\Http\Contracts\Location\LocationTownshipServiceInterface;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Vendor\VendorServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Item\Item;
use Modules\Core\Http\Requests\Item\RemoveMultiItemRequest;
use Modules\Core\Http\Requests\Item\StoreItemRequest;
use Modules\Core\Http\Requests\Item\UpdateItemRequest;
use Modules\Core\Http\Requests\Item\UploadMultiItemRequest;
use Modules\Core\Transformers\Backend\Model\Category\CategoryWithKeyResource;
use Modules\Core\Transformers\Backend\Model\Item\ItemWithKeyResource;
use Throwable;

class ItemController extends PsController
{
    private const parentPath = 'item/';

    private const indexPath = self::parentPath . 'Index';

    private const createPath = self::parentPath . 'Create';

    private const editPath = self::parentPath . 'Edit';

    private const indexRoute = 'item.index';

    private const createRoute = 'item.create';

    private const editRoute = 'item.edit';

    private const pendingItemIndexRoute = 'pending_item.index';

    private const itemVideoIconImageKey = "video_icon";

    private const itemVideoKey = "video";

    public function __construct(
        protected VendorServiceInterface $vendorService,
        protected MobileSettingServiceInterface $mobileSettingService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected ImageServiceInterface $imageService,
        protected ItemServiceInterface $itemService,
        protected SystemConfigServiceInterface $systemConfigService,
        protected SettingServiceInterface $settingService,
        protected CategoryServiceInterface $categoryService,
        protected SubcategoryServiceInterface $subcategoryService,
        protected LocationCityServiceInterface $locationCityService,
        protected LocationTownshipServiceInterface $locationTownshipService,
        protected ItemCurrencyServiceInterface $itemCurrencyService,
        protected BackendSettingServiceInterface $backendSettingService,
        protected CustomFieldServiceInterface $customFieldService,
        protected CustomFieldAttributeServiceInterface $customFieldAttributeService,
        protected UserServiceInterface $userService,
        protected LanguageServiceInterface $languageService

    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Item::class, Constants::viewAnyAbility);

        // Prepare Data
        $response = $this->prepareIndexData($request);

        return renderView(self::indexPath, $response);
    }

    public function create(Request $request)
    {

        // check permission
        $this->handlePermissionWithModel(Item::class, Constants::createAbility);

        // Prepare Data
        $dataArr = $this->prepareCreateData($request);

        // This is the special handling for
        // Item Video Upload
        $dataArr['item'] = session('item');
        /**
         * @todo move haveVendorAndCreateAccess to vendor service class
         */
        $createAsVendorIds = haveVendorAndCreateAccess(Auth::user()->id);

        $dataArr['vendor_list'] = $this->vendorService->getAll(
            relation: ['logo'],
            ids: $createAsVendorIds
        );

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreItemRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            $relationalData = $this->prepareDataCustomFields($request);

            $videoIconFile = $request->file(self::itemVideoIconImageKey);

            $videoFile = $request->file(self::itemVideoKey);

            $item = $this->itemService->save($validData, $videoIconFile, $videoFile, $relationalData);

            $msg = __('core__be_item_created');


            return back()->with([
                'item' => $item, // Send item data for video upload
            ]);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $item = $this->itemService->get($id);
        $this->handlePermissionWithModel($item, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        /**
         * @todo move haveVendorAndCreateAccess to vendor service class
         */
        $createAsVendorIds = haveVendorAndCreateAccess(Auth::user()->id);

        $dataArr['vendor_list'] = $this->vendorService->getAll(
            relation: ['logo'],
            ids: $createAsVendorIds
        );

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateItemRequest $request, $id)
    {

        try {
            // Validate the request data
            $validData = $request->validated();

            $relationalData = $this->prepareDataCustomFields($request);

            $videoIconFile = $request->file(self::itemVideoIconImageKey);

            $videoFile = $request->file(self::itemVideoKey);

            $this->itemService->update($id, $validData, $videoIconFile, $videoFile, $relationalData);

            $msg = __('core__be_item_updated');

            // return redirectView(self::indexRoute, $msg, Constants::success, ['category_filter' => $validData['category_id']]);
            return back();
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->itemService->get($id);
            $this->handlePermissionWithModel($item, Constants::deleteAbility);

            $dataArr = $this->itemService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function removeMulti(RemoveMultiItemRequest $request)
    {
        $validData = $request->validated();
        $fileName = $validData['image'];
        $imgParentId = $validData['img_parent_id'];

        if ($request->edit_mode == 0) {
            $this->imageService->delete($fileName);
            return response()->json(['success' => $fileName]);
        }

        $conds['img_type'] = 'item';
        $conds['img_parent_id'] = $imgParentId;
        $conds['img_path'] = $fileName;

        $this->imageService->get($conds)->delete();
        $this->imageService->delete($fileName);

        return response()->json(['success' => $fileName]);
    }

    public function uploadMulti(UploadMultiItemRequest $request)
    {
        try {
            $validData = $request->validated();
            $file = $request->file('file');
            return $this->itemService->updateMultiImage($validData, $file);
        } catch (Throwable $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $item = $this->itemService->get($id);

            $this->handlePermissionWithModel($item, Constants::editAbility);

            $status = $this->prepareStatusData($item);

            $this->itemService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function duplicateRow($id)
    {
        try {

            $this->handlePermissionWithModel(Item::class, Constants::createAbility);

            $item = $this->itemService->duplicateItem($id);
            $msg = __('core__be_duplicate_success', ['attribute' => $item->title]);

            if ($item->status == 1) {
                $para = ['category_filter' => $item->category_id];
                return redirectView(
                    routeName: self::indexRoute,
                    msg: $msg,
                    parameter: $para
                );
            }

            return redirectView(self::pendingItemIndexRoute, $msg);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function deeplink($id)
    {

        try {
            $this->itemService->generateDeeplink($id);
            return redirectView(self::indexRoute, __('core__be_deep_link_generate'));
        } catch (Throwable $e) {
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
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareDataCustomFields($request)
    {
        // Retrieve the 'relation' input as an array of strings
        $relationsInput = $request->input('product_relation', []);

        // Retrieve the 'relation' files as an array of files
        $relationsFiles = !empty($request->allFiles()['product_relation']) ? $request->allFiles()['product_relation'] : [];

        // Merge the input and files arrays, preserving keys
        return array_merge($relationsInput, $relationsFiles);
    }

    private function prepareIndexData($request)
    {
        // Original
        // Just for ref: Need to delete later
        // $response = $this->itemService->getItemList($request);
        // $categoriesWithCount = $this->categoryService->getAll(null, null, null, null, null, true, null, itemCount: true);
        // $response['categoriesWithCount'] = $categoriesWithCount;

        // New Implementation Phase 1

        // Prepare the default category
        $defaultCategory = $this->categoryService->get();
        $defaultCategoryId = $defaultCategory ? $defaultCategory->id : 0;
        $categoryId = $request->input('category_filter') ?? $defaultCategoryId;

        // Filters
        $filters = [
            'keyword' => $request->input('search') ?? '',
            'vendor_id' => $request->input('vendor_id') ?? '',
            'category_id' => $categoryId == 'all' ? null : $categoryId,
            'subcategory_id' => $request->input('sub_category_filter') == 'all' ? null : $request->input('sub_category_filter') ?? null,
            'location_city_id' => $request->input('city_filter') == 'all' ? null : $request->input('city_filter') ?? null,
            'location_township_id' => $request->input('township_filter') == 'all' ? null : $request->input('township_filter') ?? null,
            'added_user_id' => $request->input('owner_filter') == 'all' ? null : $request->input('owner_filter') ?? null,
            'is_sold_out' => $request->input('available_filter') == 'all' ? null : $request->input('available_filter') ?? null,
            'min_updated_date' => $request->input('updated_date_filter') == 'all' ? null : $request->input('updated_date_filter') ?? null,
            'min_added_date' => $request->input('added_date_filter') == 'all' ? null : $request->input('added_date_filter') ?? null,
            'min_price' => $request->input('price_filter') == 'all' ? null : $request->input('price_filter') ?? null,
            'status_in' => [Constants::publishItem, Constants::unpublishItem],
            'infos_filter' => [],
        ];

        // Custom Field Filters
        // @todo : We can implement this to handle dynamically
        if ($request->filled('ps_itm00002')) {
            if ($request->input('ps_itm00002') != 'all') {
                $filters['infos_filter']['ps-itm00002'] = $request->input('ps_itm00002');
            }
        }

        // Limit
        $limit = $request->input('row') ?? Constants::dataTableDefaultRow;
        $page = $request->input('page') ?? 1;

        // Orders
        $orders = [];
        if ($request->filled('sort_field')) {
            $orders = [
                $request->input('sort_field') => $request->input('sort_order'),
            ];
        }

        // Object Relations
        $relations = [
            'vendor',
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation',
        ];

        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);
// dd($this->itemService->getAll($relations, $filters, $orders, $limit));

        // Get All Items
        $items = ItemWithKeyResource::collection($this->itemService->getAll(
            relations: $relations,
            filters: $filters,
            sorting: $orders,
            noPagination: Constants::no,
            limit: $limit,
            page: $page
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::item, $this->controlFieldArr('item'));

        // Getting other reference datas
        // @todo : For this, we should migrate to lazy loading.
        $selectedCategory = $this->categoryService->get(id: $filters['category_id'], languageId: $language->id);
        $selectedSubCategory = $filters['subcategory_id'] == null ? '' : $this->subcategoryService->get(id : $filters['subcategory_id'], languageId: $language->id);
        $selectedCity = $this->locationCityService->get($filters['location_city_id']);
        $selectedTownship = $filters['location_township_id'] && $this->locationTownshipService->get($filters['location_township_id']);
        $selectedOwner = $filters['added_user_id'] && $this->userService->get($filters['added_user_id']);
        $customizeDetails = $this->customFieldAttributeService->getCustomizeUiAndDetailNestedArray(Constants::item);
        $categoriesWithCount = $this->categoryService->getAll(languageId: $language->id, noPagination: true, itemCount: true, conds: ['order_by' => Category::ordering , 'order_type' => ps_constant::ascending ]);

        // prepare for permission
        $keyValueArr = [
            'createItem' => 'create-item',
        ];

        $response = [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'items' => $items,
            'search' => $filters['keyword'],
            'selectedCategory' => $selectedCategory ? $selectedCategory : '',
            'selectedSubcategory' => $selectedSubCategory ? $selectedSubCategory : '',
            'selectedCity' => $selectedCity ? $selectedCity : '',
            'selectedTownship' => $selectedTownship ? $selectedTownship : '',
            'selectedOwner' => $selectedOwner ? $selectedOwner : '',
            'selectedAvailable' => $filters['is_sold_out'],
            'selectedPrice' => $filters['min_price'],
            'selectedAddedDate' => $filters['min_added_date'],
            'selectedUpdatedDate' => $filters['min_updated_date'],
            'itmItemType' => Constants::itmItemType,
            'itmPurchasedOption' => Constants::itmPurchasedOption,
            'itmConditionOfItem' => Constants::itmConditionOfItem,
            'customizeDetails' => $customizeDetails,
            'defaultCategoryId' => $defaultCategoryId,
            'categoryId' => $categoryId,
            'categoriesWithCount' => $categoriesWithCount,
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

        if ($request->filled('sort_field')) {
            $response['sort_field'] = $request->input('sort_field');
            $response['sort_order'] = $request->input('sort_order');
        }
// dd( $response );
        return $response;
    }

    private function prepareCreateData($request)
    {

        // Get Core Field UI
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists(Constants::item);

        // Get Custom Field UI and Detail
        $customizeHeader = $this->customFieldService->getAll(
            code: Constants::item,
            isDelete: Constants::no,
            categoryId: $request->category_id,
            withNoPag: Constants::yes
        );

        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);

        $customizeDetail = $this->customFieldAttributeService->getAll(noPagination: Constants::yes);

        // Get Category List
        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll(status: Constants::publish, noPagination: Constants::yes, languageId: $language->id, conds: ['order_by' => Category::ordering, 'order_type' => ps_constant::ascending]));
        $subCategories = $this->subcategoryService->getAll(status: Constants::publish, noPagination: Constants::yes, languageId: $language->id);

        // Get Location
        $locationCities = $this->locationCityService->getAll(status: Constants::publish, noPagination: Constants::yes);
        $locationTownships = $this->locationTownshipService->getAll(status: Constants::publish, noPagination: Constants::yes);

        // Get Currency
        $currencies = $this->itemCurrencyService->getAll(status: Constants::publish, noPagination: Constants::yes);

        // Get Settings
        $backendSettings = $this->backendSettingService->get();
        $systemConfig = $this->systemConfigService->get();
        $setting = $this->settingService->get(env: Constants::SYSTEM_CONFIG);
        $selcted_array = json_decode($setting->setting, true);
        $ref_array = json_decode($setting->ref_selection, true);

        $dataArr = [
            'customizeHeaders' => $customizeHeader,
            'customizeDetails' => $customizeDetail,
            'categories' => $categories,
            'currentCatId' => $request->category_id,
            'subcategories' => $subCategories,
            'cities' => $locationCities,
            'townships' => $locationTownships,
            'currencies' => $currencies,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'backendSettings' => $backendSettings,
            'systemConfig' => $systemConfig,
            'selected_price_type' => $selcted_array['selected_price_type']['id'],
            'selected_chat_type' => $selcted_array['selected_chat_data']['id'],
            'item_price_range' => $selcted_array['selected_price_type']['id'] == Constants::PRICE_RANGE ? $ref_array['item_price_range'] : [],
            'vendorSetting' => $backendSettings->vendor_setting == '1',

        ];

        return $dataArr;
    }

    private function prepareEditData($id)
    {
        $dataWithRelation = ['city', 'vendor', 'category', 'owner', 'cover', 'video', 'icon', "itemRelation"];
        $item = $this->itemService->get($id, $dataWithRelation);
        $code = Constants::item;

        $item = new ItemWithKeyResource($item);

        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->customFieldService->getAll(
            code: $code,
            isDelete: Constants::no,
            categoryId: $item['category_id'],
            withNoPag: Constants::yes
        );

        $language = $this->languageService->get(null, ['symbol' => $_COOKIE['activeLanguage'] ?? 'en']);

        $customizeDetail = $this->customFieldAttributeService->getAll(noPagination: Constants::yes);

        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll(status: Constants::publish, noPagination: Constants::yes, languageId: $language->id, conds: ['order_by' => Category::ordering, 'order_type' => ps_constant::ascending]));
        $subCategories = $this->subcategoryService->getAll(status: Constants::publish, noPagination: Constants::yes, languageId: $language->id);
        $locationCities = $this->locationCityService->getAll(status: Constants::publish, noPagination: Constants::yes);
        $locationTownships = $this->locationTownshipService->getAll(status: Constants::publish, noPagination: Constants::yes);
        $currencies = $this->itemCurrencyService->getAll(status: Constants::publish, noPagination: Constants::yes);
        $systemConfig = $this->systemConfigService->get();
        $backendSettings = $this->backendSettingService->get();

        $setting = $this->settingService->get(env: Constants::SYSTEM_CONFIG);
        $selcted_array = json_decode($setting->setting, true);
        $ref_array = json_decode($setting->ref_selection, true);
        $validation = $this->parepareEditValidation();
        $dataArr = [
            'item'   => $item,
            'categories' => $categories,
            'subcategories' => $subCategories,
            "cities" => $locationCities,
            "townships" => $locationTownships,
            'currencies' => $currencies,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'backendSettings' => $backendSettings,
            'paidItemProgressStatus' => Constants::paidItemProgressStatus,
            'systemConfig' => $systemConfig,
            'selected_price_type' =>  $selcted_array['selected_price_type']['id'],
            'selected_chat_type' => $selcted_array['selected_chat_data']['id'],
            'item_price_range' => $selcted_array['selected_price_type']['id'] == Constants::PRICE_RANGE ? $ref_array['item_price_range'] : [],
            'validation' => $validation,
            'vendorSetting' => $backendSettings->vendor_setting == '1',

        ];

        return $dataArr;
    }

    private function parepareEditValidation()
    {
        $conds = [
            'module_name' => Constants::item,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $coreFields = $this->coreFieldService->getAll(
            conds: $conds,
            withNoPag: Constants::yes
        );

        $validation = [];

        foreach ($coreFields as $coreField) {
            if ($coreField->field_name == 'item_image') {
                array_push($validation, 'cover');
            }
            if ($coreField->field_name == 'Item Video Icon') {
                array_push($validation, 'icon');
            }
            if ($coreField->field_name == 'item_video') {
                array_push($validation, 'video');
            }
        }
        return $validation;
    }

    private function prepareStatusData($item)
    {
        return $item->status == Constants::publishItem
            ? Constants::unpublishItem
            : Constants::publishItem;
    }

    private function controlFieldArr($status = null)
    {
        // for control
        $controlFieldArr = [];

        if ($status == Constants::pendingItem) {
            $controlFieldObj = takingForColumnProps(__('core__be_action_label'), 'action', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_detail_lbl'), 'detail', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_accept_lbl'), 'accept', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_reject_lbl'), 'reject', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_disable_lbl'), 'disable', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } elseif ($status == Constants::rejectItem) {
            $controlFieldObj = takingForColumnProps(__('core__be_action_label'), 'action', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_detail'), 'detail', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_accept_lbl'), 'accept', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_disable_lbl'), 'disable', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } elseif ($status == Constants::disableItem) {
            $controlFieldObj = takingForColumnProps(__('core__be_action_label'), 'action', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_detail'), 'detail', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_accept_lbl'), 'accept', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = takingForColumnProps(__('core__be_reject_lbl'), 'reject', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } elseif ($status == 'item') {
            $controlFieldObj = takingForColumnProps(__('core__be_action_label'), 'action', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } elseif ($status == 'itemReport' || $status == 'slowMovingItemReport' || $status == 'soldOutItemReport' || $status = 'successfulDealCountReport') {
            $controlFieldObj = takingForColumnProps(__('core__be_detail'), 'detail', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } else {
            $controlFieldObj = takingForColumnProps(__('core__be_action_label'), 'action', 'Action', false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        }

        return $controlFieldArr;
    }

    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------

    private function getCoreFieldFilteredLists($code)
    {
        if ($this->mobileSettingService->get()->is_show_subcategory == '1') {
            return $this->coreFieldService->getAll(code: $code, withNoPag: Constants::yes);
        } else {
            $notInFieldNames = ['subcategory_id@@name'];
            return $this->coreFieldService->getAll(
                code: $code,
                notInFieldNames: $notInFieldNames,
                withNoPag: Constants::yes
            );
        }
    }
}
