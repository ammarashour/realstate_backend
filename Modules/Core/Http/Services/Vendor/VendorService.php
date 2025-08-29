<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Config\Cache\ItemCache;
use App\Config\Cache\VendorCache;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Vendor\VendorInfoServiceInterface;
use App\Http\Contracts\Vendor\VendorServiceInterface;
use App\Http\Services\PsService;
use App\Models\User;
use Carbon\Carbon;
use Modules\Core\Entities\Configuration\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\Core\Http\Services\User\UserService;
use Modules\StoreFront\VendorPanel\Entities\VendorDeliverySetting;
use App\Http\Contracts\Image\ImageServiceInterface;
use Modules\Core\Entities\CoreImage;
use App\Http\Contracts\Vendor\VendorUserPermissionServiceInterface;
use App\Http\Contracts\Vendor\VendorApplicationServiceInterface;
use App\Http\Contracts\Vendor\VendorBranchServiceInterface;
use App\Http\Contracts\Vendor\VendorRoleServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class VendorService extends PsService implements VendorServiceInterface
{
    public function __construct(
        protected ImageServiceInterface $imageService,
        protected UserService $userService,
        protected MobileSettingServiceInterface $mobileSettingService,
        protected CustomFieldServiceInterface $customizeUiService,
        protected CustomFieldAttributeServiceInterface $customizeUiDetailService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected VendorBranchServiceInterface $vendorBranchService,
        protected VendorInfoServiceInterface $vendorInfoService,
        protected VendorApplicationServiceInterface $vendorApplicationService,
        protected VendorUserPermissionServiceInterface $vendorUserPermissionService,
        protected VendorRoleServiceInterface $vendorRoleService,
    ) {}

    public function setSession($id = null)
    {

        $vendorId = $id ? $id : getVendorIdFromSession();

        if ($vendorId == null) {
            //if current vendor id is null in session
            $currentSessionId = session()->getId();

            $vendorRole = $this->vendorUserPermissionService->get(Auth::id());
            $vendorRoleObj = json_decode($vendorRole->vendor_and_role);
            $vendorRoleKeys = array_keys((array) $vendorRoleObj);

            foreach ($vendorRoleKeys as $vendorRoleKey) {
                //check if role is publish
                $getRoleIds = explode(',', $vendorRoleObj->$vendorRoleKey);
                $conds = [
                    'id' => $getRoleIds,
                    'status' => 1,
                ];
                $roleIds = $this->vendorRoleService->getAll(null, $conds, Constants::publish);

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
        } elseif (! $id && $vendorId) {
            //check if current role is publish for session vendor id
            $vendorRole = $this->vendorUserPermissionService->get(Auth::id());

            $vendorRoleObj = json_decode($vendorRole->vendor_and_role);
            $getRoleIds = explode(',', $vendorRoleObj->$vendorId);
            $conds = [
                'id' => $getRoleIds,
                'status' => 1,
            ];
            $roleIds = $this->vendorRoleService->getAll(null, $conds, Constants::publish);

            $currentSessionId = session()->getId();

            if (count($roleIds) == 0) {
                //update session to publish role
                $vendorRoleKeys = array_keys((array) $vendorRoleObj);
                foreach ($vendorRoleKeys as $vendorRoleKey) {
                    //check if role is publish
                    $getRoleIds = explode(',', $vendorRoleObj->$vendorRoleKey);
                    $conds = [
                        'id' => $getRoleIds,
                        'status' => 1,
                    ];
                    $roleIds = $this->vendorRoleService->getAll(null, $conds, Constants::publish);

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
        } elseif ($id) {
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

    public function get($id, $relation = null)
    {
        $param = [$id, $relation];
        return PsCache::remember([VendorCache::BASE], VendorCache::GET_EXPIRY, $param,
            function() use($id, $relation) {

                $vendor = Vendor::when($relation != null, function ($query) use ($relation) {
                    $query->with($relation);
                })->where(Vendor::id, $id)->first();

                return $vendor;
        });
    }

    public function getAll($ownerId = null, $status = null, $relation = null, $pagPerPage = null, $conds = null, $limit = null, $offset = null, $ids = null)
    {
        // dd($conds);
        $param = [$ownerId, $status, $relation, $pagPerPage, $conds, $limit, $offset, $ids];
        return PsCache::remember([VendorCache::BASE], VendorCache::GET_ALL_EXPIRY, $param,
            function() use($ownerId, $status, $relation, $pagPerPage, $conds, $limit, $offset, $ids) {

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
                    if($status != 'all') {
                        $query->where(Vendor::tableName . '.' . Vendor::status, $status);
                    }
                })
                ->when($status == null, function($query) {
                    $query->where(Vendor::tableName . '.' . Vendor::status, '!=', Constants::vendorSpamStatus);
                })
                ->when($ids !== null, function ($query) use ($ids) {
                    $query->whereIn(Vendor::tableName . '.' . Vendor::id, $ids);
                })
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
        });
    }

    public function delete($id)
    {
        try {
            $title = $this->deleteVendor($id);

            PsCache::clear(VendorCache::BASE);

            return [
                'flag' => Constants::danger,
                'msg' => __('core__be_delete_success', ['attribute' => $title]),
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function update($id, $vendorData)
    {
        DB::beginTransaction();

        try {
            // update vendor
            $vendor = $this->updateVendor($id, $vendorData);

            //add  branch
            $this->addVendorBranch($id, $vendorData);

            $relationalData = $this->prepareDataCustomFields($vendorData);

            //save in vendor_info table
            $this->vendorInfoService->save($id, $relationalData);

            PsCache::clear(VendorCache::BASE);

            DB::commit();
            return $vendor;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function save($vendorData)
    {
        $vendor_subcription_setting = Setting::select("setting")->where("setting_env", Constants::VENDOR_SUBSCRIPTION_CONFIG)->first();
        $jsonSetting = json_decode($vendor_subcription_setting->setting, true);
        $idValue = $jsonSetting['subscription_plan'][0]['id'];

        DB::beginTransaction();
        try {

            $vendor = $this->saveVendor($vendorData, $idValue);

            // for vendor delivery setting
            $this->saveVendorDelivery($vendor);

            PsCache::clear(VendorCache::BASE);

            DB::commit();
            return $vendor;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function isUnlimitedChange($id, $isUnlimited)
    {
        DB::beginTransaction();
        try {
            $isUnlimitedData = $this->prepareIsUnlimitedData($isUnlimited);

            $vendor = $this->get($id);

            $vendor->update($isUnlimitedData);

            PsCache::clear(VendorCache::BASE);
            PsCache::clear(ItemCache::BASE);

            DB::commit();
            return $vendor;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function searching($query, $conds)
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
        if (isset($conds['product_relation']) && ! empty($conds['product_relation'])) {

            $customizeUis = CustomField::where('module_name', 'ven')->latest()->get();
            foreach ($conds['product_relation'] as $key => $value) {
                if (! empty($value['value'])) {
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

    private function saveVendor($vendorData, $idValue)
    {
        $vendor = new Vendor();
        $vendor->name = $vendorData['store_name'];
        $vendor->email = $vendorData['email'];
        $vendor->status = $idValue == "FREE" ? Constants::vendorPendingStatus : Constants::vendorSpamStatus;
        $vendor->owner_user_id = $vendorData['login_user_id'];
        $vendor->currency_id = $vendorData['currency_id'];
        $vendor->added_user_id = Auth::user()->id;
        if (isset($vendorData['expired_date'])) {
            $vendor->expired_date = $vendorData['expired_date'];
        }
        $vendor->save();

        return $vendor;
    }

    private function saveVendorDelivery($vendor)
    {
        $deliverySetting = new VendorDeliverySetting();
        $deliverySetting->vendor_id = $vendor->id;
        $deliverySetting->delivery_setting = 0;
        $deliverySetting->delivery_charges = 0;
        $deliverySetting->added_user_id = Auth::user()->id;
        $deliverySetting->save();
    }

    private function updateVendor($id, $vendorData)
    {
        $vendor = $this->get($id);
        if ($vendor && $vendor->status == Constants::vendorAcceptStatus) {
            return ['error' => __('core__be_vendor_already_accepted'), 'message'  => __('core__be_vendor_already_accepted')];
        }

        $vendor->name = $vendorData['store_name'];
        $vendor->email = $vendorData['email'];
        $vendor->status = Constants::vendorPendingStatus;
        $vendor->currency_id = $vendorData['currency_id'];
        $vendor->updated_user_id = Auth::user()->id;
        if (isset($vendorData['expired_date'])) {
            $vendor->expired_date = $vendorData['expired_date'];
        }
        $vendor->save();

        $imgParentId = $vendor->id;
        if ($vendorData[Constants::vendorLogoImgType]) {
            $this->updateVendorLogo($imgParentId, $vendorData);
        }
        if ($vendorData[Constants::vendorBanner1ImgType]) {
            $this->updateVendorBanner1($imgParentId, $vendorData);
        }
        if ($vendorData[Constants::vendorBanner2ImgType]) {
            $this->updateVendorBanner2($imgParentId, $vendorData);
        }
        return $vendor;
    }

    private function updateVendorLogo($imgParentId, $vendorData)
    {
        $conds[CoreImage::imgParentId] = $imgParentId;
        $conds[CoreImage::imgType] = Constants::vendorLogoImgType;

        $image = $this->imageService->get($conds);
        $this->imageService->update($image->id, $vendorData->file(Constants::vendorLogoImgType), $conds);
    }

    private function updateVendorBanner1($imgParentId, $vendorData)
    {
        $conds[CoreImage::imgParentId] = $imgParentId;
        $conds[CoreImage::imgType] = Constants::vendorBanner1ImgType;

        $image = $this->imageService->get($conds);
        $this->imageService->update($image->id, $vendorData->file(Constants::vendorBanner1ImgType), $conds);
    }
    private function updateVendorBanner2($imgParentId, $vendorData)
    {
        $conds[CoreImage::imgParentId] = $imgParentId;
        $conds[CoreImage::imgType] = Constants::vendorBanner2ImgType;

        $image = $this->imageService->get($conds);
        dd($image);
        $this->imageService->update($image->id, $vendorData->file(Constants::vendorBanner2ImgType), $conds);
    }

    private function addVendorBranch($id, $vendorData)
    {
        $branchIds = [];
        if ($vendorData->vendorBranches && is_array($vendorData->vendorBranches) && count($vendorData->vendorBranches) > 0) {
            foreach ($vendorData->vendorBranches as $vendorBranch) {
                $branch = $this->vendorBranchService->update($vendorBranch['id'], $vendorBranch);
                array_push($branchIds, $branch->id);
            }
        }
        //delete other branches
       $this->vendorBranchService->deleteAll($id, $branchIds);
    }

    private function deleteVendor($id)
    {
        $vendor = $this->get($id);
        $title = $vendor->name;
        $application = $this->vendorApplicationService->get(null, $id);
        Storage::delete('document/' . $application->document);
        $vendor->delete();
        $application->delete();

        return $title;
    }
    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareDataCustomFields($vendorData)
    {
        // Retrieve the 'relation' input as an array of strings
        $relationsInput = $vendorData->input('vendorRelation', []);

        // Retrieve the 'relation' files as an array of files
        $relationsFiles = ! empty($vendorData->allFiles()['vendorRelation']) ? $vendorData->allFiles()['vendorRelation'] : [];

        // Merge the input and files arrays, preserving keys
        return array_merge($relationsInput, $relationsFiles);
    }

    private function prepareIsUnlimitedData($isUnlimited)
    {
        return [Vendor::isUnlimited => $isUnlimited];
    }
}
