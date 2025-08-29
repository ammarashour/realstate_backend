<?php

namespace Modules\Core\Http\Services\Item;

use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Financial\ItemCurrencyServiceInterface;
use App\Http\Services\PsService;
use Carbon\Carbon;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Item;
use Modules\Core\Http\Services\Category\CategoryService;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Http\Services\Category\SubcategoryService;
use Modules\Core\Http\Services\UserService;
use Modules\Core\Transformers\Backend\NoModel\SlowMovingItem\SlowMovingItemWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\SlowMovingItemReport\SlowMovingItemReportWithKeyResource;
use Modules\Core\Transformers\Backend\Model\Product\ProductWithKeyResource;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Exports\SlowMovingItemReportExport;
use Modules\Core\Http\Services\Location\LocationCityService;
use Modules\Core\Http\Services\Location\LocationTownshipService;
use Modules\Core\Transformers\Backend\Model\Category\CategoryWithKeyResource;

class SlowMovingItemService extends PsService
{
    protected $customUiDetailCoreKeysIdCol, $viewAnyAbility, $publish, $unBan, $dataWithRelation, $csvFileName, $successFlag, $dangerFlag, $itemService, $imageService, $categoryService, $subcategoryService, $locationCityService, $locationTownshipService, $userService,
        $itmTableName, $itmIdCol, $itmCatIdCol, $itmSubCatIdCol, $itmItemLocationIdCol, $itmItemLocationTownshipIdCol, $itmUserIdCol,
        $itmItemCurrencyIdCol, $itmShopIdCol, $itmIsPaidCol, $itmAddedUserIdCol;

    public function __construct(protected BackendSettingServiceInterface $backendSettingService, ItemService $itemService, ImageService $imageService, CategoryService $categoryService, UserService $userService, SubcategoryService $subcategoryService, LocationCityService $locationCityService, LocationTownshipService $locationTownshipService, protected ItemCurrencyServiceInterface $itemCurrencyService)
    {
        $this->itemService = $itemService;
        $this->imageService = $imageService;
        $this->categoryService = $categoryService;
        $this->subcategoryService = $subcategoryService;
        $this->locationCityService = $locationCityService;
        $this->locationTownshipService = $locationTownshipService;
        $this->userService = $userService;

        $this->dangerFlag = Constants::danger;
        $this->successFlag = Constants::success;
        $this->publish = Constants::publish;
        $this->unBan = Constants::unBan;

        $this->itmTableName = Item::tableName;
        $this->itmIdCol = Item::id;
        $this->itmCatIdCol = Item::categoryId;
        $this->itmSubCatIdCol = Item::subCategoryId;
        $this->itmItemLocationIdCol = Item::itemLocationId;
        $this->itmItemLocationTownshipIdCol = Item::itemLocationTownshipId;
        $this->itmUserIdCol = Item::userId;
        $this->itmItemCurrencyIdCol = Item::itemCurrencyId;
        $this->itmShopIdCol = Item::shopId;
        $this->itmIsPaidCol = Item::isPaid;
        $this->itmAddedUserIdCol = Item::addedUserId;

        $this->dataWithRelation = ['city', 'township', 'category', 'subcategory', 'owner', 'cover', 'video', 'icon', "itemRelation"];
        $this->csvFileName = "slow_moving_item";

        $this->viewAnyAbility = Constants::viewAnyAbility;

        $this->itmItemType = Constants::itmItemType;
        $this->itmDealOption = Constants::itmDealOption;
        $this->itmPurchasedOption = Constants::itmPurchasedOption;
        $this->itmConditionOfItem = Constants::itmConditionOfItem;

        $this->customUiDetailCoreKeysIdCol = CustomFieldAttribute::coreKeysId;
        $this->customUiDetailIdCol = CustomFieldAttribute::id;


        $this->customUiModuleNameCol = CustomField::moduleName;
        $this->customUiUiTypeIdCol = CustomField::uiTypeId;
    }

    public function index($request)
    {
        // $slow_moving_item_limit = $this->backendSettingService->getBackendSetting()->slow_moving_item_limit;
        // $items = Item::with($this->dataWithRelation)->whereDate('added_date', '<=', Carbon::now()->subDays($slow_moving_item_limit))
        //     ->latest()
        //     ->get();

        // $conds = ['added_date', '<=', Carbon::now()->subDays($slow_moving_item_limit)];
        // $code = $this->code;
        $customizeUis = CustomField::where($this->customUiModuleNameCol, Constants::item)->where($this->customUiUiTypeIdCol, 'uit00001')->latest()->get();

        $productRelations = [];
        //        $uis = [];
        //        foreach($customizeUis as $customizeUi){
        //
        //            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id',$customizeUi->core_keys_id)->get()->toArray();
        //        }
        $uis = CustomFieldAttribute::whereIn($this->customUiDetailCoreKeysIdCol, $customizeUis->pluck($this->customUiDetailCoreKeysIdCol))->get()->groupBy($this->customUiDetailCoreKeysIdCol)->toArray();

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['category_id'] = $request->input('category_filter') == 'all' ? null  : $request->category_filter;
        $conds['added_date_range'] = $request->input('date_filter') == "all" ? null : $request->date_filter;

        if ($request->deal_option_filter) {
            $productRelations[] =  ['core_keys_id' => $this->itmDealOption, 'value' => $request->deal_option_filter];
        }

        if ($request->purchase_option_filter) {
            $productRelations[] =  ['core_keys_id' => $this->itmPurchasedOption, 'value' => $request->purchase_option_filter];
        }

        if (!empty($productRelations)) {
            $conds['product_relation'] = $productRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $itemRelation = [
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation'
        ];

        $items = SlowMovingItemWithKeyResource::collection($this->itemService->getSlowMovingItems($itemRelation, false, $row, null, null, $conds, null, null, null, null, null, null, null));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->itemService->takingForColumnAndFilterOption();
        $showProductCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing item arr object with new format
        $changedProductObj = $items;
        $selected_category = $this->categoryService->get($conds['category_id']);
        $selected_purchaseOption = CustomFieldAttribute::where($this->customUiDetailIdCol, $request->purchase_option_filter)->first();
        $selected_dealOption = CustomFieldAttribute::where($this->customUiDetailIdCol, $request->deal_option_filter)->first();
        // dd($selected_purchaseOption);


        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,

                'items' => $changedProductObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedCategory' => $selected_category ? new CategoryWithKeyResource($selected_category) : '',

                'selectedDealOption' => $selected_dealOption ? $selected_dealOption : '',
                'selectedPurchaseOption' => $selected_purchaseOption ? $selected_purchaseOption : '',
                'selectedDate' => $conds['added_date_range'],
                'itmPurchasedOption' => $this->itmPurchasedOption,
                'itmItemType' => $this->itmItemType,
                'itmDealOption' => $this->itmDealOption,
                'uis' => $uis,
            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,

                'items' => $changedProductObj,
                'search' => $conds['searchterm'],
                'selectedCategory' => $selected_category ? new CategoryWithKeyResource($selected_category) : '',
                'selectedDealOption' => $selected_dealOption ? $selected_dealOption : '',
                'selectedPurchaseOption' => $selected_purchaseOption ? $selected_purchaseOption : '',
                'selectedDate' => $conds['added_date_range'],
                'itmPurchasedOption' => $this->itmPurchasedOption,
                'itmItemType' => $this->itmItemType,
                'itmDealOption' => $this->itmDealOption,
                'uis' => $uis,
            ];
        }

        return $dataArr;
    }

    public function edit($id)
    {
        $catRelation = ['subcategory'];

        $item = $this->itemService->getItem($id, $this->dataWithRelation);
        $categories = CategoryWithKeyResource::collection($this->categoryService->getAll($catRelation, Constants::publish));
        $subcategories = $this->subcategoryService->getAll(null, $this->publish);
        $cities = $this->locationCityService->getAll(['township'], $this->publish, null, null, null, true);
        $townships = $this->locationTownshipService->getAll(null, $this->publish, null, null, null, true);
        $currencies = $this->itemCurrencyService->getAll(status: Constants::publish, noPagination: Constants::yes);
        $owners = $this->userService->getUsers(null, $this->publish, $this->unBan);

        $code = Constants::item;
        $coreFieldFilterSettings = $this->itemService->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->itemService->getCustomizeFields($code, null, null, 0, null, null, $item->category_id);
        $customizeDetail = $this->itemService->getCustomizeFieldAttrs();

        $backendSettings = $this->backendSettingService->get();

        $conds = [
            'module_name' => Constants::item,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = CoreField::where($conds)->get();

        $validation = [];

        foreach ($core_headers as $core_header) {
            if ($core_header->field_name == 'item_image') {
                array_push($validation, 'cover');
            }
            if ($core_header->field_name == 'Item Video Icon') {
                array_push($validation, 'icon');
            }
            if ($core_header->field_name == 'item_video') {
                array_push($validation, 'video');
            }
        }


        $dataArr = [
            "item" => $item,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'cities' => $cities,
            'townships' => $townships,
            'currencies' => $currencies,
            'owners' => $owners,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'backendSettings' => $backendSettings,
            'validation' => $validation
        ];
        return $dataArr;
    }

    public function csvExport()
    {
        $filename = newFileNameForExport($this->csvFileName);
        return (new SlowMovingItemReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function destroy($id)
    {
        //delete in items table
        $item = $this->itemService->getItem($id);

        $images = $this->imageService->getImages($id, 'item_related');
        $this->imageService->deleteImagesFromBoth($images);

        //delete in item_infos table
        $productRelations = $this->itemService->getValuesForCustomizeField('', $id);

        $title = $item->title;
        $item->delete();

        $this->itemService->deleteCustomizeFieldData($productRelations);

        $dataArr = [
            'msg' => __('core__be_delete_success', ['attribute' => $title]),
            'flag' => $this->dangerFlag,
        ];

        return $dataArr;
    }


    public function slowMovingItemReportIndex($request)
    {
        $customizeUis = CustomField::where($this->customUiModuleNameCol, 'itm')->where($this->customUiUiTypeIdCol, 'uit00001')->latest()->get();
        $productRelations = [];
        //        $uis = [];
        //        foreach($customizeUis as $customizeUi){
        //
        //            $uis[$customizeUi->core_keys_id] = CustomFieldAttribute::where('core_keys_id',$customizeUi->core_keys_id)->get()->toArray();
        //        }
        $uis = CustomFieldAttribute::whereIn($this->customUiDetailCoreKeysIdCol, $customizeUis->pluck($this->customUiDetailCoreKeysIdCol))->get()->groupBy($this->customUiDetailCoreKeysIdCol)->toArray();

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['category_id'] = $request->input('category_filter') == 'all' ? null  : $request->category_filter;
        $conds['added_date_range'] = $request->input('date_filter') == "all" ? null : $request->date_filter;

        if ($request->deal_option_filter) {
            $productRelations[] =  ['core_keys_id' => $this->itmDealOption, 'value' => $request->deal_option_filter];
        }

        if ($request->purchase_option_filter) {
            $productRelations[] =  ['core_keys_id' => $this->itmPurchasedOption, 'value' => $request->purchase_option_filter];
        }

        if ($request->item_type_filter) {
            $productRelations[] =  ['core_keys_id' => $this->itmItemType, 'value' => $request->item_type_filter];
        }

        if (!empty($productRelations)) {
            $conds['product_relation'] = $productRelations;
        }
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $itemRelation = [
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation'
        ];

        $items = SlowMovingItemWithKeyResource::collection($this->itemService->getSlowMovingItems($itemRelation, false, $row, null, null, $conds, null, null, null, null, null, null, null));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->itemService->takingForColumnAndFilterOption();
        $showProductCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing item arr object with new format
        $changedProductObj = $items;
        // $categories = $this->categoryService->getCategories(null, $this->publish);
        $selected_category = $this->categoryService->get($conds['category_id']);
        $selected_purchaseOption = CustomFieldAttribute::where($this->customUiDetailIdCol, $request->purchase_option_filter)->first();
        $selected_dealOption = CustomFieldAttribute::where($this->customUiDetailIdCol, $request->deal_option_filter)->first();
        $selected_itemType = CustomFieldAttribute::where($this->customUiDetailIdCol, $request->item_type_filter)->first();


        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'items' => $changedProductObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedCategory' => $selected_category ? $selected_category : '',
                'selectedItemType' => $selected_itemType ? $selected_itemType : '',
                'selectedDealOption' => $selected_dealOption ? $selected_dealOption : '',
                'selectedPurchaseOption' => $selected_purchaseOption ? $selected_purchaseOption : '',
                'selectedItemType' => $request->item_type_filter,
                'selectedDate' => $conds['added_date_range'],
                'itmPurchasedOption' => $this->itmPurchasedOption,
                'itmItemType' => $this->itmItemType,
                'itmDealOption' => $this->itmDealOption,
                'uis' => $uis,
            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'items' => $changedProductObj,
                'search' => $conds['searchterm'],
                'selectedCategory' => $selected_category ? $selected_category : '',
                'selectedItemType' => $selected_itemType ? $selected_itemType : '',
                'selectedDealOption' => $selected_dealOption ? $selected_dealOption : '',
                'selectedPurchaseOption' => $selected_purchaseOption ? $selected_purchaseOption : '',
                'selectedDate' => $conds['added_date_range'],
                'itmPurchasedOption' => $this->itmPurchasedOption,
                'itmItemType' => $this->itmItemType,
                'itmDealOption' => $this->itmDealOption,
                'uis' => $uis,
            ];
        }

        return $dataArr;
    }

    public function slowMovingItemReportShow($id)
    {
        $catRelation = ['subcategory'];
        $relation = ['category', 'subcategory', 'city', 'township', 'currency', 'owner', 'itemRelation', 'cover', 'video', 'icon'];
        $item = $this->itemService->getItem($id, $relation);
        $categories = $this->categoryService->getAll($catRelation, $this->publish);
        $subcategories = $this->subcategoryService->getAll(null, $this->publish);
        $cities = $this->locationCityService->getAll(['township'], $this->publish, null, null, null, true);
        $townships = $this->locationTownshipService->getAll(null, $this->publish, null, null, null, true);
        $currencies = $this->itemCurrencyService->getAll(status: Constants::publish, noPagination: Constants::yes);
        $owners = $this->userService->getUsers(null, $this->publish, $this->unBan);

        $code = Constants::item;
        $coreFieldFilterSettings = $this->itemService->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->itemService->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->itemService->getCustomizeFieldAttrs();

        $dataArr = [
            "item" => $item,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'cities' => $cities,
            'townships' => $townships,
            'currencies' => $currencies,
            'owners' => $owners,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings
        ];
        return $dataArr;
    }
}
