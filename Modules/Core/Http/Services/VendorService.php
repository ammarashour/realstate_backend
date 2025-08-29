<?php

namespace Modules\Core\Http\Services;

use App\Config\Cache\VendorCache;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Services\PsService;
use Modules\Core\Entities\Vendor\Vendor;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Cache\Store;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\VendorInfo;
use Modules\Core\Entities\Vendor\VendorRole;
use Modules\Core\Entities\CustomizeUi;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Entities\Vendor\VendorBranch;
use Modules\Core\Entities\CustomizeUiDetail;
use Modules\Core\Entities\Vendor\VendorApplication;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Entities\Vendor\VendorUserPermission;
use Modules\Core\Entities\CoreFieldFilterSetting;
use Modules\Core\Entities\ScreenDisplayUiSetting;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorApiResource;
use Modules\Core\Transformers\Backend\Model\Vendor\VendorWithKeyResource;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorBranchApiResource;
use Modules\StoreFront\VendorPanel\Entities\VendorDeliverySetting;

/**
 * @deprecated
 */
class VendorService extends PsService
{
    const editRoute = "vendor_info.index";

    protected $mobileSettingService, $imageService, $userService, $vendorPendingStatus, $vendorSuccessStatus, $vendorRejectStatus;

    public function __construct(ImageService $imageService, UserService $userService, MobileSettingServiceInterface $mobileSettingService)
    {
        $this->userService = $userService;
        $this->mobileSettingService = $mobileSettingService;
        $this->imageService = $imageService;

        $this->vendorPendingStatus = Constants::vendorPendingStatus;
        $this->vendorSuccessStatus = Constants::vendorAcceptStatus;
        $this->vendorRejectStatus = Constants::vendorRejectStatus;

        $this->code = Constants::vendor;
    }

    public function index($request)
    {
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;
        $relation = ['owner', 'logo', 'banner_1', 'banner_2', 'vendorBranch'];

        // check permission start
        $checkPermission = $this->checkPermission(Constants::viewAnyAbility, Vendor::class, "admin.index");
        // check permission end

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $conds['searchterm'] = $request->input('search') ?? '';

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $vendors = VendorWithKeyResource::collection($this->getVendors(null, Constants::vendorAcceptStatus, $relation, $row, $conds));


        $columnAndColumnFilter = $this->takingForColumnAndFilterOption(Constants::vendorAcceptStatus);
        $showVendorCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        if ($conds['order_by']) {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'vendorList' => $vendors,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'showVendorCols' => $showVendorCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
            ];
        } else {
            $dataArr = [
                "checkPermission" => $checkPermission,
                'vendorList' => $vendors,
                'showVendorCols' => $showVendorCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
            ];
        }

        return $dataArr;
    }

    public function getVendorApplication($id = null, $vendorId = null)
    {
        $appliction = VendorApplication::when($id !== null, function ($query) use ($id) {
            $query->where(VendorApplication::id, $id);
        })->when($vendorId !== null, function ($query) use ($vendorId) {
            $query->where(VendorApplication::vendorId, $vendorId);
        })->first();

        return $appliction;
    }

    public function getVendor($id, $relation = null)
    {
        $vendor = Vendor::when($relation != null, function ($query) use ($relation) {
            $query->with($relation);
        })->where(Vendor::id, $id)->first();

        return $vendor;
    }

    public function setSession($id = null)
    {

        $vendorId = $id ? $id : getVendorIdFromSession();

        if ($vendorId == null) {
            //if current vendor id is null in session

            $currentSessionId = session()->getId();

            $vendorRole = VendorUserPermission::where('user_id', Auth::id())->first();
            $vendorRoleObj = json_decode($vendorRole->vendor_and_role);
            $vendorRoleKeys = array_keys((array)$vendorRoleObj);

            foreach ($vendorRoleKeys as $vendorRoleKey) {
                //check if role is publish
                $getRoleIds = explode(',', $vendorRoleObj->$vendorRoleKey);
                $roleIds = VendorRole::whereIn('id', $getRoleIds)
                    ->where('status', 1)
                    ->pluck('id')
                    ->toArray();

                if (count($roleIds) > 0 && isVendorEnable($vendorRoleKey)) {
                    DB::table('psx_custom_sessions')->where('id', '=', $currentSessionId)->update(
                        [
                            'data_obj' => [
                                'vendor_id' => $vendorRoleKey,
                            ],
                            'last_activity' => Carbon::now()->getTimestamp(),
                        ]
                    );
                    break;
                }
            }
        } else if (!$id && $vendorId) {
            //check if current role is publish for session vendor id
            $vendorRole = VendorUserPermission::where('user_id', Auth::id())->first();

            $vendorRoleObj = json_decode($vendorRole->vendor_and_role);
            $getRoleIds = explode(',', $vendorRoleObj->$vendorId);
            $roleIds = VendorRole::whereIn('id', $getRoleIds)
                ->where('status', 1)
                ->pluck('id')
                ->toArray();

            $currentSessionId = session()->getId();

            if (count($roleIds) == 0) {
                //update session to publish role
                $vendorRoleKeys = array_keys((array)$vendorRoleObj);
                foreach ($vendorRoleKeys as $vendorRoleKey) {
                    //check if role is publish
                    $getRoleIds = explode(',', $vendorRoleObj->$vendorRoleKey);
                    $roleIds = VendorRole::whereIn('id', $getRoleIds)
                        ->where('status', 1)
                        ->pluck('id')
                        ->toArray();

                    if (count($roleIds) > 0 && isVendorEnable($vendorRoleKey)) {
                        DB::table('psx_custom_sessions')->where('id', '=', $currentSessionId)->update(
                            [
                                'data_obj' => [
                                    'vendor_id' => $vendorRoleKey,
                                ],
                                'last_activity' => Carbon::now()->getTimestamp(),
                            ]
                        );
                        break;
                    }
                }
            }
        } else if ($id) {
            //for update id
            DB::table('psx_custom_sessions')->where('id', '=', session()->getId())->update(
                [
                    'data_obj' => [
                        'vendor_id' => $id,
                    ],
                    'last_activity' => Carbon::now()->getTimestamp(),
                ]
            );
        }

        return true;
    }

    public function getVendorByIds($relation = null, $ids = [])
    {
        $vendors = Vendor::when($relation != null, function ($query) use ($relation) {
            $query->with($relation);
        })->whereIn(Vendor::id, $ids)->get();
        return $vendors;
    }

    public function getVendors($ownerId = null, $status = null, $relation = null, $pagPerPage = null, $conds = null, $limit = null, $offset = null)
    {
        // dd($conds);
        $vendors = Vendor::select(Vendor::tableName . '.*')
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($conds) {
                if ($conds['order_by'] == 'owner_name') {
                    $q->select([Vendor::tableName . '.*', User::tableName . '.' . User::name . ' as owner_name', User::tableName . '.' . User::status . ' as user_status']);
                    $q->join(User::tableName, Vendor::tableName . '.' . Vendor::ownerUserId, '=', User::tableName . '.' . User::id);
                }
            })
            ->when($relation != null, function ($query) use ($relation) {
                $query->with($relation);
            })
            ->when($ownerId !== null, function ($query) use ($ownerId) {
                $query->where('owner_user_id', $ownerId);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where(Vendor::tableName . '.' . Vendor::status, $status);
            })
            ->where(Vendor::tableName . '.' . Vendor::status, '!=', Constants::vendorSpamStatus)
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })->orderBy(Vendor::tableName . '.' . Vendor::status, 'asc')->orderBy(Vendor::tableName . '.' . Vendor::id, 'desc');

        if ($pagPerPage) {
            $vendors = $vendors->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $vendors = $vendors->get();
        }

        return $vendors;
    }

    public function getVendorBranches($limit = null, $offset = null, $noPagination = null,  $pagPerPage = null, $conds = null)
    {

        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $vendorBranches = VendorBranch::select(VendorBranch::tableName . '.*')
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == VendorBranch::vendorId . '@@name') {
                    $q->join(Vendor::tableName, Vendor::tableName . '.' . Vendor::id, '=', Vendor::vendorId);
                    $q->select(Vendor::tableName . '.' . Vendor::name . ' as vendor_name', Vendor::tableName . '.*');
                }
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->branchSearching($query, $conds);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(VendorBranch::tableName . '.added_date', 'desc')->orderBy(VendorBranch::tableName . '.' . VendorBranch::name, 'asc');
            });
        if ($pagPerPage) {
            $vendorBranches = $vendorBranches->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $vendorBranches = $vendorBranches->get();
        }
        return $vendorBranches;
    }

    public function branchSearching($query, $conds)
    {
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorBranch::tableName . '.' . VendorBranch::name, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds[VendorBranch::vendorId]) && $conds[VendorBranch::vendorId]) {
            $query->where(VendorBranch::tableName . '.' . VendorBranch::vendorId, $conds[VendorBranch::vendorId]);
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(VendorBranch::tableName . '.' . VendorBranch::addedUserId, $conds['added_user_id']);
        }


        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(VendorBranch::tableName . '.' . VendorBranch::id, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function update($id, $request)
    {
        DB::beginTransaction();

        try {
            // update vendor
            $vendor = $this->getVendor($id);
            $vendor->updated_user_id = Auth::user()->id;
            $vendor->update($request->validated());

            // update vendor photo
            $imgParentId = $vendor->id;
            $this->imageService->updateOrCreateImage($request, "logo", $imgParentId, Constants::vendorLogoImgType);
            $this->imageService->updateOrCreateImage($request, "banner_1", $imgParentId, Constants::vendorBanner1ImgType);
            $this->imageService->updateOrCreateImage($request, "banner_2", $imgParentId, Constants::vendorBanner2ImgType);

            //add  branch
            $branchIds = [];
            if ($request->vendorBranches && is_array($request->vendorBranches) && count($request->vendorBranches) > 0) {
                foreach ($request->vendorBranches as $vendorBranch) {
                    if (isset($vendorBranch['name']) || isset($vendorBranch['phone']) || isset($vendorBranch['address']) || isset($vendorBranch['description'])) {
                        if (isset($vendorBranch['id'])) {
                            $updateBranch = VendorBranch::find($vendorBranch['id']);
                            $updateBranch->vendor_id = $id;
                            $updateBranch->name = $vendorBranch['name'];
                            $updateBranch->email = $vendor->email;
                            $updateBranch->phone = $vendorBranch['phone'];
                            $updateBranch->address = $vendorBranch['address'];
                            $updateBranch->description = $vendorBranch['description'];
                            $updateBranch->updated_user_id = Auth::user()->id ?? 1;
                            $updateBranch->update();

                            array_push($branchIds, $updateBranch->id);
                        } else {
                            $newBranch = new VendorBranch();
                            $newBranch->vendor_id = $id;
                            $newBranch->name = $vendorBranch['name'];
                            $newBranch->email = $vendor->email;
                            $newBranch->phone = $vendorBranch['phone'];
                            $newBranch->address = $vendorBranch['address'];
                            $newBranch->description = $vendorBranch['description'];
                            $newBranch->updated_user_id = Auth::user()->id ?? 1;
                            $newBranch->save();

                            array_push($branchIds, $newBranch->id);
                        }
                    }
                }
            }
            //delete other branches
            VendorBranch::where('vendor_id', $id)
                ->whereNotIn('id', $branchIds)
                ->delete();

            // save in vendor_info table
            $customizeHeaders = $this->getCustomizeFields($this->code, null, null, 0);
            $customizeHeaderIds = $this->getValueByPluck($customizeHeaders, CustomField::coreKeysId);

            $this->storeCustomFieldValues($request, $vendor, $customizeHeaderIds);

            DB::commit();
            PsCache::clear(VendorCache::BASE);
            return redirectView(self::editRoute, $vendor);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirectView(self::editRoute, $e->getMessage(), Constants::danger, $id);
        }
    }

    public function storeCustomFieldValues($request, $vendor, $coreKeysIds)
    {

        if (!empty($request->vendor_relation)) {
            foreach ($coreKeysIds as $coreKeysId) {
                foreach ($request->vendor_relation as $key => $value) {
                    if (is_array($value)) {
                        $coreKeysIdFromReq = $value['core_keys_id'];
                        $valueFromReq = $value['value'];
                    } else {
                        $coreKeysIdFromReq = $key;
                        $valueFromReq = $value;
                    }

                    if ($coreKeysIdFromReq === $coreKeysId) {
                        $itemRelation = VendorInfo::firstOrNew([
                            VendorInfo::id => $vendor->id,
                            VendorInfo::coreKeyId => $coreKeysIdFromReq
                        ]);

                        if (is_file($valueFromReq)) {
                            $file = $this->checkFileInCustomFieldValue($valueFromReq);
                        }

                        $itemRelation->vendor_id = $vendor->id;

                        if ($valueFromReq === false) {
                            $itemRelation->value = 0;
                        }
                        if (is_file($valueFromReq)) {
                            if (str_contains($valueFromReq->getMimeType(), 'image')) {
                                $itemRelation->value = $file;
                            }
                        }
                        if (!is_file($valueFromReq) && $valueFromReq !== false) {
                            $itemRelation->value = $valueFromReq;
                        }

                        $customizeHeaders = $this->getCustomizeFields($this->code, null, null, 0);
                        foreach ($customizeHeaders as $key2 => $customizeHeader) {
                            if ($coreKeysIdFromReq === $customizeHeader->core_keys_id) {
                                $itemRelation->ui_type_id = $customizeHeader->ui_type_id;
                                $itemRelation->core_keys_id = $customizeHeader->core_keys_id;
                            }
                        }

                        if (Auth::check()) {
                            $itemRelation->added_user_id = Auth::id();
                        } else {
                            $itemRelation->added_user_id = 0;
                        }

                        if ($valueFromReq != null) {
                            $itemRelation->save();
                        }
                    }
                }
            }
        }
    }

    public function getValueByPluck($values, $pluckColumn)
    {
        $pluckedValues = $values->pluck($pluckColumn)->unique()->values();
        return $pluckedValues;
    }


    public function searching($query, $conds)
    {
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(Vendor::tableName . '.' . Vendor::name, 'like', '%' . $search . '%')
                    ->orWhere(Vendor::tableName . '.' . Vendor::email, 'like', '%' . $search . '%')
                    ->orWhere(Vendor::tableName . '.' . Vendor::address, 'like', '%' . $search . '%');
            });
        }
        // status
        if (isset($conds['status'])) {
            $status = $conds['status'];
            $query->where(Vendor::tableName . '.' . Vendor::status, $status);
        }

        // owner id
        if (isset($conds['owner_user_id']) && $conds['owner_user_id']) {
            $ownerId = $conds['owner_user_id'];
            $query->where(Vendor::tableName . '.' . Vendor::ownerUserId, $ownerId);
        }
        if (isset($conds['product_relation']) && !empty($conds['product_relation'])) {

            $customizeUis = CustomField::where('module_name', 'ven')->latest()->get();
            foreach ($conds['product_relation'] as $key => $value) {
                if (!empty($value['value'])) {
                    foreach ($customizeUis as $CustomFieldAttribute) {
                        if ($value['core_keys_id'] == $CustomFieldAttribute->core_keys_id) {
                            if ($CustomFieldAttribute->ui_type_id == Constants::dropDownUi || $CustomFieldAttribute->ui_type_id == Constants::radioUi || $CustomFieldAttribute->ui_type_id == Constants::multiSelectUi) {
                                $detail = CustomFieldAttribute::find($value['value']);

                                if ($detail) {
                                    $query->having($value['core_keys_id'] . '@@name', $detail->name);
                                }
                            } else {
                                $detail = CustomFieldAttribute::find($value['value']);
                                if ($detail) {
                                    $query->having($value['core_keys_id'], $detail->name);
                                }
                            }
                        }
                    }
                }
            }
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy(Vendor::tableName . '.' . Vendor::id, $conds['order_type']);
            } elseif ($conds['order_by'] == 'name') {
                $query->orderBy(Vendor::tableName . '.' . Vendor::name, $conds['order_type']);
            } elseif ($conds['order_by'] == 'owner_name') {
                // $query->select([Vendor::tableName.'.*', User::tableName.'.'.User::name.' as owner_name']);
                // $query->join(User::tableName, Vendor::tableName.'.'.Vendor::ownerUserId, '=', User::tableName.'.'.User::id);
                $query->orderBy('owner_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'added_date') {
                $query->orderBy(Vendor::tableName . '.' . Vendor::CREATED_AT, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(Vendor::tableName . '.' . Vendor::CREATED_AT, 'desc');
        }

        if (isset($conds['added_date_range']) && $conds['added_date_range']) {
            $added_date_filter = $conds['added_date_range'];
            if ($added_date_filter[1] == '') {
                $added_date_filter[1] = Carbon::now();
            }
            $query->whereBetween(Vendor::tableName . '.' . Vendor::CREATED_AT, $added_date_filter);
        }

        return $query;
    }

    public function storeFromApi($request)
    {
        $vendor_subcription_setting = Setting::select("setting")->where("setting_env", Constants::VENDOR_SUBSCRIPTION_CONFIG)->first();
        $jsonSetting = json_decode($vendor_subcription_setting->setting, true);
        $idValue = $jsonSetting['subscription_plan'][0]['id'];

        DB::beginTransaction();
        try {
            $vendor = new Vendor();
            $vendor->name = $request->store_name;
            $vendor->email = $request->email;
            $vendor->status = $idValue == "FREE" ? Constants::vendorPendingStatus : Constants::vendorSpamStatus;
            $vendor->owner_user_id = $request->login_user_id;
            $vendor->currency_id = $request->currency_id;
            $vendor->added_user_id = Auth::user()->id;
            if (isset($request->expired_date)) {
                $vendor->expired_date = $request->expired_date;
            }
            $vendor->save();

            // for vendor delivery setting
            $deliverySetting = new VendorDeliverySetting();
            $deliverySetting->vendor_id = $vendor->id;
            $deliverySetting->delivery_setting = 0;
            $deliverySetting->delivery_charges = 0;
            $deliverySetting->added_user_id = Auth::user()->id;
            $deliverySetting->save();

            DB::commit();

            return $vendor;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function updateFromApi($request)
    {
        $vendor = $this->getVendor($request->vendor_id);
        if ($vendor && $vendor->status == Constants::vendorAcceptStatus) {
            return ['error' => __('core__be_vendor_already_accepted'), 'message'  => __('core__be_vendor_already_accepted')];
        }
        DB::beginTransaction();
        try {
            $vendor->name = $request->store_name;
            $vendor->email = $request->email;
            $vendor->status = $this->vendorPendingStatus;
            $vendor->currency_id = $request->currency_id;
            $vendor->updated_user_id = Auth::user()->id;
            if (isset($request->expired_date)) {
                $vendor->expired_date = $request->expired_date;
            }
            $vendor->save();

            DB::commit();
            PsCache::clear(VendorCache::BASE);
            return $vendor;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function updateVendorExpiredDate($id, $request)
    {
        $vendor = $this->getVendor($id);
        if (isset($vendor)) {
            // $expiredDate = $vendor->expired_date == null ? Carbon::now() : $vendor->expired_date;
            DB::beginTransaction();
            try {
                $vendor->expired_date = $request->expired_date;
                $vendor->status = $request->status;
                $vendor->updated_user_id = Auth::user()->id;
                $vendor->update();

                DB::commit();
                PsCache::clear(VendorCache::BASE);
                return $vendor;
            } catch (\Throwable $e) {
                DB::rollBack();
                return ['error' => $e->getMessage()];
            }
        }
    }

    public function getVendorsFromApi($request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $ownerId = $request->owner_user_id;
        $status = isset($request->owner_user_id) ? $request->status : Constants::vendorAcceptStatus;
        $relation = ['logo', 'banner_1', 'banner_2'];

        $vendorList = $this->getVendors($ownerId, $status, $relation, null, null, $limit, $offset);

        return $vendorList;
    }

    public function searchFromApi($request)
    {
        $offset = $request->offset;
        $limit = $request->limit;

        $conds['searchterm'] = $request->searchterm;
        $conds['owner_user_id'] = $request->owner_user_id;
        $conds['order_by'] = $request->order_by;
        $conds['order_type'] = $request->order_type;
        $conds['status'] = $request->status;
        $conds['product_relation'] = $request->product_relation;
        $relation = ['logo', 'banner_1', 'banner_2'];

        $vendor = $this->getVendors(null, null, $relation, null, $conds, $limit, $offset);

        return $vendor;
    }

    public function noDataError($offset, $data)
    {
        if ($offset > 0 && $data->isEmpty()) {
            // no paginate data
            $data = [];
            return responseDataApi($data);
        } else if ($data->isEmpty()) {
            // no data db
            return responseMsgApi(__('core__api_no_data'), Constants::noContentStatusCode, Constants::successStatus);
        }
    }

    public function show($id)
    {
        $relation = ['owner', 'logo', 'banner_1', 'banner_2', 'vendorBranch'];

        $coreFieldFilterSettings = CoreField::where(CoreField::moduleName, Constants::vendor)->get();
        $branchesCoreFieldFilterSettings = CoreField::where(CoreField::moduleName, Constants::vendorBranches)->get();
        $vendor = new VendorWithKeyResource($this->getVendor($id, $relation));
        $customizeHeaders = $this->getCustomizeFields(Constants::vendor, null, null, 0, null, null);
        $customizeDetail = $this->getCustomizeFieldAttrs();

        $dataArr = [
            'vendor' => $vendor,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'branchesCoreFieldFilterSettings' => $branchesCoreFieldFilterSettings,
            'customizeHeaders' => $customizeHeaders,
            "customizeDetails" => $customizeDetail,
        ];

        return $dataArr;
    }

    public function destroy($id)
    {
        // check permission start
        $checkPermission = $this->checkPermission(Constants::deleteAbility, Vendor::class, "admin.index");
        // check permission end

        $vendor = Vendor::find($id);
        $title = $vendor->name;
        $application = $this->getVendorApplication(null, $id);
        Storage::delete('document/' . $application->document);
        $vendor->delete();
        $application->delete();

        $dataArr = [
            "checkPermission" => $checkPermission,
            'flag' => Constants::danger,
            'msg' => __('core__be_delete_success', ['attribute' => $title]),
        ];
        PsCache::clear(VendorCache::BASE);
        return $dataArr;
    }

    public function takingForColumnAndFilterOption($status = null)
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showProductCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];

        if ($status == Constants::vendorPendingStatus) {
            $controlFieldObj = $this->takingForColumnProps(__('core__be_action_label'), "action", "Action", false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = $this->takingForColumnProps(__('core__be_detail_lbl'), "detail", "Action", false, 20);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = $this->takingForColumnProps(__('core__be_accept_lbl'), "accept", "Action", false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = $this->takingForColumnProps(__('core__be_reject_lbl'), "reject", "Action", false, 0);
            array_push($controlFieldArr, $controlFieldObj);
        } else {
            $controlFieldObj = $this->takingForColumnProps(__('action_label'), "action", "Action", false, 0);
            array_push($controlFieldArr, $controlFieldObj);

            $controlFieldObj = $this->takingForColumnProps(__('core__be_detail'), "detail", "Action", false, 20);
            array_push($controlFieldArr, $controlFieldObj);
        }

        $code = Constants::vendor;
        $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField(Constants::hide, $code);
        $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField(Constants::show, $code);
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
                $uiHaveAttribute = [Constants::dropDownUi, Constants::radioUi];
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

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;
        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
            $hideShowCoreAndCustomFieldObj->action = __('core__be_action_label');
        }

        return $hideShowCoreAndCustomFieldObj;
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

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {

        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where(CustomField::isDelete, Constants::unDelete);
        }, 'coreField' => function ($q) {
            $q->where(CoreField::moduleName, Constants::vendor);
        }])
            ->where(CoreField::moduleName, $code)
            ->where(DynamicColumnVisibility::isShow, $isShown)
            ->get();
        return $hiddenShownForFields;
    }

    public function getCustomizeFields($code = null, $dataWithRelation = null, $coreKeysId = null, $isDel = null, $limit = null, $offset = null)
    {
        $customizeHeader  =  CustomField::when($dataWithRelation, function ($q, $dataWithRelation) {
            $q->with($dataWithRelation);
        })
            ->when($isDel !== null,  function ($q) use ($isDel) {
                $q->where(CustomField::isDelete, $isDel);
            })
            ->when($code, function ($q, $code) {
                $q->where(CustomField::moduleName, $code);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($coreKeysId, function ($q, $coreKeysId) {
                $q->where(CustomField::coreKeysId, $coreKeysId);
            })
            ->get();
        return $customizeHeader;
    }

    public function getCustomizeFieldAttrs($customizeHeaderId = null)
    {
        $customizeDetails = CustomFieldAttribute::when($customizeHeaderId, function ($q, $data) {
            $q->where(CustomFieldAttribute::coreKeysId, $data);
        })
            ->latest()->get();
        return $customizeDetails;
    }

    public function getValuesForCustomizeField($customizeHeaderIds = null, $vendorId = null)
    {
        $values = VendorInfo::when($customizeHeaderIds, function ($q, $data) {
            $q->whereIn(VendorInfo::coreKeyId, $data);
        })
            ->when($vendorId, function ($q, $data) {
                $q->where(VendorInfo::vendorId, $data);
            })
            ->get();
        return $values;
    }

    public function deleteCustomizeFieldData($vendorRelations)
    {
        foreach ($vendorRelations as $vendorRelation) {
            delImageFromCustomFieldValue($vendorRelation, Constants::uploadPathForDel, Constants::thumb1xPathForDel, Constants::thumb2xPathForDel, Constants::thumb3xPathForDel);
        }
        VendorInfo::destroy($vendorRelations->pluck('id'));
        PsCache::clear(VendorCache::BASE);
    }

    public function downloadDocument($applicationId = null, $vendorId = null)
    {
        $application = $this->getVendorApplication($applicationId, $vendorId);

        $file_exist = File::exists(public_path() . '/document/' . $application->document);
        if ($file_exist) {
            $file = public_path('document/' . $application->document);
            return response()->download($file);
        }
    }

    public function getVendorBranchesFromApi($request)
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;
        $offset = $request->offset;
        $limit = $request->limit ? $request->limit : ($defaultLimit ? $defaultLimit :  9);

        $conds['searchterm'] = $request->keyword;
        $conds['vendor_id'] = $request->vendor_id;
        $conds['order_by'] = $request->order_by;
        $conds['order_type'] = $request->order_type;

        $vendorBranches = $this->getVendorBranches($limit, $offset, 1, null, $conds);
        $data = VendorBranchApiResource::collection($vendorBranches);

        $hasError = noDataError($request->offset, $data);

        if ($hasError)
            return $hasError;
        else {
            return $data;
        }
    }
}
