<?php

namespace Modules\Core\Http\Services\Item;

use App\Config\Cache\ItemCache;
use App\Config\ps_constant;

use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item\ComplaintItem;
use Modules\Core\Entities\Item\ComplaintItemStatus;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Exports\ComplaintReportExport;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Http\Services\Category\CategoryService;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Transformers\Api\App\V1_0\Product;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Transformers\Backend\Model\Item\ComplaintItemWithKeyResource;

class ComplaintItemService extends PsService
{
    protected $forbiddenStatusCode, $loginUserIdParaApi, $deviceTokenParaApi, $complaintItemTextNoteCol, $complaintItemReportedUserIdCol, $complaintItemItemIdCol, $complaintItemReportedItemStatusIdCol, $successFlag, $complaintReportCsvFileName, $dangerFlag, $warningFlag, $descending, $publish, $complaintItemIdCol, $categoryService, $complaintItemApiRelation, $itemService, $createdStatusCode, $badRequestStatusCode, $internalServerErrorStatusCode, $successStatus, $noContentStatusCode, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol, $userAccessApiTokenService;

    public function __construct(CategoryService $categoryService, ItemService $itemService, UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;

        $this->complaintItemIdCol = ComplaintItem::id;
        $this->complaintItemReportedUserIdCol = ComplaintItem::reportedUserId;
        $this->complaintItemItemIdCol = ComplaintItem::itemId;
        $this->complaintItemReportedItemStatusIdCol = ComplaintItem::reportedItemStatusId;
        $this->complaintItemTextNoteCol = ComplaintItem::textNote;
        $this->categoryService = $categoryService;
        $this->publish = Constants::publish;
        $this->descending = Constants::descending;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
        $this->successFlag = Constants::success;
        $this->createdStatusCode = Constants::createdStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;
        $this->noContentStatusCode = Constants::noContentStatusCode;

        $this->complaintReportCsvFileName = "complaint_item_report";

        $this->complaintItemApiRelation = ['item'];

        $this->itemService = $itemService;

        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->code = Constants::itemReport;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;

        $this->customUiIsDelCol = CustomField::isDelete;
        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $complaintItem = new ComplaintItem();

            if (isset($request->item_id) && !empty($request->item_id))
                $complaintItem->item_id = $request->item_id;

            if (isset($request->reported_user_id) && !empty($request->reported_user_id))
                $complaintItem->reported_user_id = $request->reported_user_id;

            if (isset($request->text_note) && !empty($request->text_note))
                $complaintItem->text_note = $request->text_note;

            if (isset($request->reported_item_status_id) && !empty($request->reported_item_status_id))
                $complaintItem->reported_item_status_id = $request->reported_item_status_id;
            else
                $complaintItem->reported_item_status_id = 1;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $complaintItem->added_user_id = $request->added_user_id;
            else
                $complaintItem->added_user_id = $this->itemService->getItem($request->item_id)->added_user_id;

            $complaintItem->save();

            PsCache::clear(ItemCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $complaintItem;
    }

    public function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            if ($search) {
                $query->join('psx_items', 'psx_items.id', '=', 'psx_item_reports.item_id');
            }

            $query->where(function ($query) use ($search) {
                $query->where('psx_items.title', 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['item_id']) && $conds['item_id']) {
            $query->where('item_id', $conds['item_id']);
        }

        if (isset($conds['reported_user_id']) && $conds['reported_user_id']) {
            $query->where('reported_user_id', $conds['reported_user_id']);
        }

        if (isset($conds['reported_item_status_id']) && $conds['reported_item_status_id']) {
            $query->where('reported_item_status_id', $conds['reported_item_status_id']);
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas('added_date', function ($q) use ($date_filter) {
                $q->where('added_date', $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where('added_user_id', $conds['added_user_id']);
        }

        if (isset($conds['date_range']) && $conds['date_range']) {
            $date_filter = $conds['date_range'];
            if ($date_filter[1] == '') {
                $date_filter[1] = Carbon::now();
            }
            $query->whereBetween('added_date', $date_filter);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy('id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'item@@title') {
                $query->orderBy('item_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'added_user_id@@name') {
                $query->orderBy('owner_name', $conds['order_type']);
            } else if ($conds['order_by'] == 'complete') {
                $query->orderBy('reported_item_status_id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getComplaintItems($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null, $reportedUserId = null)
    {

        $sort = '';
        // $pagPerPage = null;
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $complaintItems = ComplaintItem::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == 'item@@title') {
                    $q->join('psx_items', 'psx_items.id', '=', 'psx_item_reports' . '.' . 'item_id');
                    $q->select('psx_items.title as item_name', 'psx_item_reports.*');
                }
                if ($sort == 'added_user_id@@name') {
                    $q->join('users', 'users.id', '=', 'psx_item_reports' . '.' . 'added_user_id');
                    $q->select('users.name as owner_name', 'psx_item_reports.*');
                }
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($status, function ($q, $status) {
                $q->where($this->paidItmStatusCol, $status);
            })
            ->when($reportedUserId, function ($q, $reportedUserId) {
                $q->where('reported_user_id', $reportedUserId);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest('psx_item_reports.added_date');

        if ($pagPerPage) {
            $complaintItems = $complaintItems->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $complaintItems = $complaintItems->get();
        } else {
            $complaintItems = $complaintItems->get();
        }

        return $complaintItems;
    }

    public function getComplaintItem($id = null, $relation = null)
    {
        $complaintItem = ComplaintItem::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($id, function ($q, $id) {
                $q->where($this->complaintItemIdCol, $id);
            })
            ->latest()
            ->first();

        return $complaintItem;
    }

    public function deleteAllBy($conds = null)
    {
        DB::beginTransaction();
        try {
            ComplaintItem::when($conds, function ($query, $conds) {
                $query->where($conds);
            })->delete();

            PsCache::clear(ItemCache::BASE);

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function changingStatus($id)
    {
        try {
            $item = $this->getComplaintItem($id);
            if ($item->reported_item_status_id == 1) {
                $item->reported_item_status_id = 2;
            } else {
                $item->reported_item_status_id = 1;
            }
            $item->updated_user_id = Auth::user()->id;
            $item->update();

            PsCache::clear(ItemCache::BASE);
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // -----------
    public function index($request)
    {

        $code = $this->code;

        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, ComplaintItem::class, "admin.index");

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['reported_item_status_id'] = $request->input('status_filter') == 'all' ? null  : $request->status_filter;
        $conds['date_range'] = $request->input('date_filter') == "all" ? null : $request->date_filter;

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $ralations = ['item', 'reported_user', 'reported_item_status', 'owner', 'editor'];
        $items = ComplaintItemWithKeyResource::collection($this->getComplaintItems($ralations, null, null, null, $conds, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showProductCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        $types = ComplaintItemStatus::get();

        // changing item arr object with new format
        $changedProductObj = $items;

        if ($conds['order_by']) {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'items' => $changedProductObj,
                'types' => $types,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedStatus' => $conds['reported_item_status_id'],
                'selectedDate' => $conds['date_range'],
            ];
        } else {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'items' => $changedProductObj,
                'types' => $types,
                'search' => $conds['searchterm'],
                'selectedStatus' => $conds['reported_item_status_id'],
                'selectedDate' => $conds['date_range'],
            ];
        }

        return $dataArr;
    }

    public function show($id)
    {
        // check permission start
        $checkPermission = $this->checkPermission($this->viewAnyAbility, ComplaintItem::class, "admin.index");
        // check permission end

        $complaint_item = $this->getComplaintItem($id);

        $dataWithRelation = ['city', 'category', 'township', 'subcategory', 'currency', 'owner', 'cover', 'video', 'icon', "itemRelation"];

        $item = $this->itemService->getItem($complaint_item->item_id, $dataWithRelation);
        $code = Constants::item;

        $coreFieldFilterSettings = $this->itemService->getCoreFieldFilteredLists($code);
        $customizeHeader = $this->itemService->getCustomizeFields($code, null, null, 0);
        $customizeDetail = $this->itemService->getCustomizeFieldAttrs();

        $dataArr = [
            'checkPermission' => $checkPermission,
            'item'   => $item,
            "customizeHeaders" => $customizeHeader,
            "customizeDetails" => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings
        ];

        return $dataArr;
    }

    public function destroy($id)
    {
        $item = $this->getComplaintItem($id);

        // check permission
        $checkPermission = $this->checkPermission($this->deleteAbility, $item, "admin.index");

        if ($item->reported_item_status_id == 2) {
            $item->delete();
            $status = [
                'checkPermission' => $checkPermission,
                'msg' => 'The row has been deleted successfully.',
                'flag' => $this->dangerFlag,
            ];

            PsCache::clear(ItemCache::BASE);

            return $status;
        } else {
            $status = [
                'checkPermission' => $checkPermission,
                'msg' => 'The ' . $item->name . 'cannot be deleted because it is not close status.',
                'flag' => $this->warningFlag,
            ];

            PsCache::clear(ItemCache::BASE);

            return $status;
        }
    }

    public function statusChange($id)
    {
        $msg = $this->changingStatus($id);
        if ($msg) {
            $status = [
                'msg' => $msg,
                'flag' => $this->dangerFlag,
            ];
            return $status;
        }

        $status = [
            'msg' => 'The status has been updated successfully.',
            'flag' => $this->successFlag,
        ];

        PsCache::clear(ItemCache::BASE);

        return $status;
    }

    public function csvExport()
    {
        $filename = newFileNameForExport($this->complaintReportCsvFileName);
        return (new ComplaintReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function getComplaintItemIds($user_id)
    {
        // get_favourite_get api
        // search_post api (item search)
        // get_item_by_followuser_post api
        // offer_list_post api
        // get_buyer_seller_list_post api

        // get all complaint items by user_id
        $conds_report[$this->complaintItemReportedUserIdCol] = $user_id;
        $complaintItems = $this->getComplaintItems(null, null, null, null, $conds_report, true);
        $complaintItemIds = [];
        // complaint items existed
        if (!empty($complaintItems)) {

            // get all the complaint item ids
            $i = 0;
            for ($x = 0; $x < count($complaintItems); $x++) {
                if (!in_array($complaintItems[$x]->item_id, $complaintItemIds)) {
                    $complaintItemIds[$i++] = $complaintItems[$x]->item_id;
                }
            }
        }
        return $complaintItemIds;
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;

        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
            $hideShowCoreAndCustomFieldObj->action = 'Action';
        }

        return $hideShowCoreAndCustomFieldObj;
    }

    public function takingForColumnAndFilterOption()
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showProductCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];
        $controlFieldObj = $this->takingForColumnProps(__('core__be_action_label'), "action", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);
        $controlFieldObj = $this->takingForColumnProps(__('core__be_detail'), "detail", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        $code = $this->code;
        $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->hide, $code);
        $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->show, $code);
        $hideShowForCoreAndCustomFields = $shownForCoreAndCustomField->merge($hiddenForCoreAndCustomField);

        foreach ($hideShowForCoreAndCustomFields as $showFields) {
            if ($showFields->coreField !== null) {

                $label = $showFields->coreField->label_name;
                $field = $showFields->coreField->field_name;
                $colName = $showFields->coreField->field_name;
                $type = $showFields->coreField->data_type;
                $isShowSorting = $showFields->coreField->is_show_sorting;
                $ordering = $showFields->coreField->ordering;

                $cols = $colName;
                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->coreField->module_name;
                $keyId = $showFields->coreField->id;

                $coreFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $coreFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $coreFieldObjForColumnFilter);
                array_push($hideShowCoreFieldForColumnArr, $coreFieldObjForColumnsProps);
                array_push($showProductCols, $cols);
            }
            if ($showFields->customizeField !== null) {

                $label = $showFields->customizeField->name;
                $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
                if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
                    $field = $showFields->customizeField->core_keys_id . "@@name";
                } else {
                    $field = $showFields->customizeField->core_keys_id;
                }
                $type = $showFields->customizeField->data_type;
                $isShowSorting = $showFields->customizeField->is_show_sorting;
                $ordering = $showFields->customizeField->ordering;

                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->customizeField->module_name;
                $keyId = $showFields->customizeField->core_keys_id;

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            "arrForColumnProps" => $sortedColumnArr,
            "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
            "showCoreField" => $showProductCols,
        ];
        return $dataArr;
    }

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {
        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where($this->customUiIsDelCol, $this->unDelete);
        }, 'coreField' => function ($q) {
            $q->where($this->coreFieldFilterModuleNameCol, $this->code);
        }])
            ->where($this->coreFieldFilterModuleNameCol, $code)
            ->where($this->screenDisplayUiIsShowCol, $isShown)
            ->get();
        return $hiddenShownForFields;
    }

    public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->id = $id;
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->key = $field;
        $hideShowCoreAndCustomFieldObj->hidden = $hidden;
        $hideShowCoreAndCustomFieldObj->module_name = $moduleName;
        $hideShowCoreAndCustomFieldObj->key_id = $keyId;

        return $hideShowCoreAndCustomFieldObj;
    }

    // for api
    public function noDataError($offset, $data)
    {
        if ($offset > 0 && $data->isEmpty()) {
            // no paginate data
            $data = [];
            return responseDataApi($data);
        } else if ($data->isEmpty()) {
            // no data db
            return responseMsgApi(__('core__no_data'), $this->noContentStatusCode, $this->successStatus);
        }
    }

    public function indexFromApi($request)
    {
        $offset = $request->offset;
        $limit = $request->limit;
        $conds['reported_user_id'] = $request->login_user_id;

        $complaintItemApiRelation = $this->complaintItemApiRelation;
        $complaintItems = $this->getComplaintItems($complaintItemApiRelation, null, $limit, $offset, $conds, true);
        $complaintItemIds = [];
        $i = 0;
        if (count($complaintItems) > 0) {
            for ($x = 0; $x < count($complaintItems); $x++) {
                if (!in_array($complaintItems[$x]->item_id, $complaintItemIds)) {
                    $complaintItemIds[$i++] = $complaintItems[$x]->item_id;
                }
            }
        }

        $itemApiRelation = ['category', 'subcategory', 'city', 'township', 'currency', 'owner', 'itemRelation', 'cover', 'video', 'icon'];
        $item_conds['ids'] = $complaintItemIds;
        // $items = $this->itemService->getItems($itemApiRelation, true, null, $limit, $offset, null, $item_conds);
        $items = $this->itemService->getItems($itemApiRelation, true, null, $limit, null, null, $item_conds);

        return $items;
    }

    public function storeFromApi($request)
    {

        // check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($request->login_user_id, $deviceToken);

        if (empty($userAccessApiToken)) {
            $msg = __('core__api_update_no_permission', [], $request->language_symbol);
            return ['error' =>  $msg, 'status' =>  $this->forbiddenStatusCode];
        }
        // check permission end


        $reported_user_id = $request->reported_user_id;
        $added_user_id = $this->itemService->getItem($request->item_id)->added_user_id;

        if ($reported_user_id == $added_user_id) {
            return  ['error' => __('complaint_item__api_unable_to_report', [], $request->language_symbol), 'status' =>  $this->badRequestStatusCode];
        }

        $conds['item_id'] = $request->item_id;
        $conds['reported_user_id'] = $request->reported_user_id;
        $conds['reported_item_status_id'] = 1;
        $complaintItems = $this->getComplaintItems(null, null, null, null, $conds, true);

        if (count($complaintItems) > 0) {
            return  ['error' => __('complaint_item__api_already_report', [], $request->language_symbol), 'status' =>  $this->badRequestStatusCode];
        }

        $complaintItem = $this->store($request);

        if (isset($complaintItem['error'])) {
            return  ['error' => __('complaint_item__api_db_error', [], $request->language_symbol), 'status' =>  $this->internalServerErrorStatusCode];
        }

        PsCache::clear(ItemCache::BASE);

        return  ['success' => __('complaint_item__api_success_item_report', [], $request->language_symbol), 'status' =>  $this->okStatusCode];
    }

    public function makeColumnHideShown($request)
    {
        $hideShowFields = $request->value;
        //        foreach ($hideShowFields as $hideShowField){
        //            $DynamicColumnVisibility = DynamicColumnVisibility::find($hideShowField[$this->screenDisplayUiIdCol]);
        //            $DynamicColumnVisibility->is_show = $hideShowField['hidden'] ? $this->hide : $this->show;
        //            $DynamicColumnVisibility->update();
        //        }
        foreach ($hideShowFields as $hideShowField) {
            $hideShowFieldData[] = [
                'id' => $hideShowField['id'],
                'is_show' => $hideShowField['hidden'] ? $this->hide : $this->show,
            ];
        }
        DynamicColumnVisibility::upsert(
            $hideShowFieldData,
            ['id'],
            ['is_show']
        );
    }
}
