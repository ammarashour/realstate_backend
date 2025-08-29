<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use stdClass;
use Carbon\Carbon;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\ItemInfo;
use Modules\Core\Constants\Constants;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Modules\StoreFront\VendorPanel\Entities\Order;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\StoreFront\VendorPanel\Entities\ShippingAndBilling;
use Modules\StoreFront\VendorPanel\Entities\ShippingAndBillingForUser;
use Modules\StoreFront\VendorPanel\Entities\UserAddress;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\ShippingAndBillingForUser\BillingForUserApiResource;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\ShippingAndBillingForUser\DefaultShippingAndBillingForUserApiResource;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\ShippingAndBillingForUser\ShippingForUserApiResource;

class UserAddressService extends PsService
{

    protected $userAccessApiTokenService;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
    }

    public function getUserAddresses($userId = null, $orderCol = null, $orderType = "desc")
    {
        $userAddresses = UserAddress::when($userId, function ($q, $userId) {
            $q->where(UserAddress::userId, $userId);
        })
            ->when($orderCol, function ($q) use ($orderCol, $orderType) {
                $q->orderBy($orderCol, $orderType);
            })

            ->get();
        return $userAddresses;
    }

    public function getUserAddress($userId = null, $isDefaultShippingInfo = null, $isDefaultBillingInfo = null, $orderCol = null, $orderType = "desc", $id = null)
    {
        $userAddress = UserAddress::when($userId, function ($q, $userId) {
            $q->where(UserAddress::userId, $userId);
        })
            ->when($isDefaultShippingInfo, function ($q, $isDefaultShippingInfo) {
                $q->where(UserAddress::isSaveShippingInfoForNextTime, $isDefaultShippingInfo);
            })
            ->when($isDefaultBillingInfo, function ($q, $isDefaultBillingInfo) {
                $q->where(UserAddress::isSaveBillingInfoForNextTime, $isDefaultBillingInfo);
            })
            ->when($orderCol, function ($q) use ($orderCol, $orderType) {
                $q->orderBy($orderCol, $orderType);
            })
            ->when($id, function ($q, $id) {
                $q->where(UserAddress::id, $id);
            })
            ->first();
        return $userAddress;
    }

    public function getAllShippingForUserFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $userId = $request->login_user_id;
        $data = $this->getUserAddresses($userId);
        if ($data->isEmpty()) {
            return responseMsgApi(__('core__api_no_data'), Constants::noContentStatusCode, Constants::successStatus);
        } else {
            $defaultShippingInfo = $this->getUserAddress($userId, Constants::default);
            if (!empty($defaultShippingInfo)) {
                $getAllShippingInfos = $this->getUserAddresses($userId, UserAddress::isSaveShippingInfoForNextTime, "desc");
            } else {
                $getAllShippingInfos = $this->getUserAddresses($userId, UserAddress::addedDate, "desc");
            }
            $shippingForUsers = ShippingForUserApiResource::collection($getAllShippingInfos);
            return responseDataApi($shippingForUsers, Constants::okStatusCode);
        }
    }

    public function getAllBillingForUserFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $userId = $request->login_user_id;
        $data = $this->getUserAddresses($userId);
        if ($data->isEmpty()) {
            return responseMsgApi(__('core__api_no_data'), Constants::noContentStatusCode, Constants::successStatus);
        } else {
            $defaultBillingInfo = $this->getUserAddress($userId, null, Constants::default);
            if (!empty($defaultBillingInfo)) {
                $getAllBillingInfos = $this->getUserAddresses($userId, UserAddress::isSaveBillingInfoForNextTime, "desc");
            } else {
                $getAllBillingInfos = $this->getUserAddresses($userId, UserAddress::addedDate, "desc");
            }
            $billingForUsers = BillingForUserApiResource::collection($getAllBillingInfos);
            return responseDataApi($billingForUsers, Constants::okStatusCode);
        }
    }

    public function getDefaultShippingAndBillingForUserFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $userId = $request->login_user_id;
        $data = $this->getUserAddresses($userId);
        if ($data->isEmpty()) {
            return responseMsgApi(__('core__api_no_data'), Constants::noContentStatusCode, Constants::successStatus);
        } else {
            $defaultBillingInfo = $this->getUserAddress($userId, null, Constants::default);
            $defaultShippingInfo = $this->getUserAddress($userId, Constants::default);

            if (!empty($defaultBillingInfo)) {
                $defaultBillingInfoData = $this->getUserAddress($userId, null, null, UserAddress::isSaveBillingInfoForNextTime, "desc");
            } else {
                $defaultBillingInfoData = $this->getUserAddress($userId, null, null, UserAddress::addedDate, "desc");
            }

            if (!empty($defaultShippingInfo)) {
                $defaultShippingInfoData = $this->getUserAddress($userId, null, null, UserAddress::isSaveShippingInfoForNextTime, "desc");
            } else {
                $defaultShippingInfoData = $this->getUserAddress($userId, null, null, UserAddress::addedDate, "desc");
            }
            $defaultDataObj = new \stdClass();
            $defaultDataObj->defaultShippingInfo = $defaultShippingInfoData;
            $defaultDataObj->defaultBillingInfo = $defaultBillingInfoData;
            $billingForUsers = new DefaultShippingAndBillingForUserApiResource($defaultDataObj);
            return responseDataApi($billingForUsers, Constants::okStatusCode);
        }
    }

    public function addNewShippingAddressFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        DB::beginTransaction();
        try {
            $userId = $request->login_user_id;
            $shippingInfoForNextTime = $request->is_save_shipping_info_for_next_time;
            $billingInfoForNextTime = $request->is_save_billing_info_for_next_time;

            // save in psx_user_addresses table
            if (empty($this->getUserAddress($userId))) {
                $isSaveShippingInfoForNextTime = 1;
                $isSaveBillingInfoForNextTime = 1;
            } else {
                if (!empty($shippingInfoForNextTime)) {
                    UserAddress::where("added_user_id", $userId)
                        ->update([UserAddress::isSaveShippingInfoForNextTime => ps_constant::notSaveForNextTime]);
                }

                if (!empty($billingInfoForNextTime)) {
                    UserAddress::where("added_user_id", $userId)
                        ->update([UserAddress::isSaveBillingInfoForNextTime => ps_constant::notSaveForNextTime]);
                }
                $isSaveShippingInfoForNextTime = $shippingInfoForNextTime;
                $isSaveBillingInfoForNextTime = $billingInfoForNextTime;
            }

            $userAddress = new UserAddress();
            $userAddress->user_id = $userId;
            $userAddress->first_name = $request->shipping_first_name;
            $userAddress->last_name = $request->shipping_last_name;
            $userAddress->email = $request->shipping_email;
            $userAddress->phone_no = $request->shipping_phone_no;
            $userAddress->address = $request->shipping_address;
            $userAddress->country = $request->shipping_country;
            $userAddress->state = $request->shipping_state;
            $userAddress->city = $request->shipping_city;
            $userAddress->postal_code = $request->shipping_postal_code;
            $userAddress->is_save_shipping_info_for_next_time = !empty($isSaveShippingInfoForNextTime) ? $isSaveShippingInfoForNextTime : 0;
            $userAddress->is_save_billing_info_for_next_time = !empty($isSaveBillingInfoForNextTime) ? $isSaveBillingInfoForNextTime : 0;
            $userAddress->added_user_id = $userId;
            $userAddress->save();

            DB::commit();

            $msg = __('new_shipping_address_added_success', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::okStatusCode, Constants::successStatus);
        } catch (\Throwable $e) {

            DB::rollBack();
            $msg = __('core__api_db_error', [], $request->language_symbol);
            return responseMsgApi($e->getMessage(), Constants::badRequestStatusCode);
        }
    }

    public function addNewBillingAddressFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        DB::beginTransaction();
        try {
            $userId = $request->login_user_id;
            $shippingInfoForNextTime = $request->is_save_shipping_info_for_next_time;
            $billingInfoForNextTime = $request->is_save_billing_info_for_next_time;

            // save in psx_user_addresses table
            if (empty($this->getUserAddress($userId))) {
                $isSaveShippingInfoForNextTime = 1;
                $isSaveBillingInfoForNextTime = 1;
            } else {
                if (!empty($shippingInfoForNextTime)) {
                    UserAddress::where("added_user_id", $userId)
                        ->update([UserAddress::isSaveShippingInfoForNextTime => ps_constant::notSaveForNextTime]);
                }

                if (!empty($billingInfoForNextTime)) {
                    UserAddress::where("added_user_id", $userId)
                        ->update([UserAddress::isSaveBillingInfoForNextTime => ps_constant::notSaveForNextTime]);
                }
                $isSaveShippingInfoForNextTime = $shippingInfoForNextTime;
                $isSaveBillingInfoForNextTime = $billingInfoForNextTime;
            }

            $userAddress = new UserAddress();
            $userAddress->user_id = $userId;
            $userAddress->first_name = $request->billing_first_name;
            $userAddress->last_name = $request->billing_last_name;
            $userAddress->email = $request->billing_email;
            $userAddress->phone_no = $request->billing_phone_no;
            $userAddress->address = $request->billing_address;
            $userAddress->country = $request->billing_country;
            $userAddress->state = $request->billing_state;
            $userAddress->city = $request->billing_city;
            $userAddress->postal_code = $request->billing_postal_code;
            $userAddress->is_save_shipping_info_for_next_time = !empty($isSaveShippingInfoForNextTime) ? $isSaveShippingInfoForNextTime : 0;
            $userAddress->is_save_billing_info_for_next_time = !empty($isSaveBillingInfoForNextTime) ? $isSaveBillingInfoForNextTime : 0;
            $userAddress->added_user_id = $userId;
            $userAddress->save();

            DB::commit();

            $msg = __('new_billing_address_added_success', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::okStatusCode, Constants::successStatus);
        } catch (\Throwable $e) {

            DB::rollBack();
            $msg = __('core__api_db_error', [], $request->language_symbol);
            return responseMsgApi($e->getMessage(), Constants::badRequestStatusCode);
        }
    }

    public function editShippingAddressFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        DB::beginTransaction();
        try {
            $id = $request->id;
            $userId = $request->login_user_id;

            $shippingInfoForNextTime = $request->is_save_shipping_info_for_next_time;
            $billingInfoForNextTime = $request->is_save_billing_info_for_next_time;

            if (!empty($shippingInfoForNextTime)) {
                UserAddress::where("added_user_id", $userId)
                    ->update([UserAddress::isSaveShippingInfoForNextTime => ps_constant::notSaveForNextTime]);
            }

            if (!empty($billingInfoForNextTime)) {
                UserAddress::where("added_user_id", $userId)
                    ->update([UserAddress::isSaveBillingInfoForNextTime => ps_constant::notSaveForNextTime]);
            }

            // update in psx_user_addresses table
            $userAddress = $this->getUserAddress(id: $id);
            $userAddress->user_id = $userId;
            $userAddress->first_name = $request->shipping_first_name;
            $userAddress->last_name = $request->shipping_last_name;
            $userAddress->email = $request->shipping_email;
            $userAddress->phone_no = $request->shipping_phone_no;
            $userAddress->address = $request->shipping_address;
            $userAddress->country = $request->shipping_country;
            $userAddress->state = $request->shipping_state;
            $userAddress->city = $request->shipping_city;
            $userAddress->postal_code = $request->shipping_postal_code;
            $userAddress->is_save_shipping_info_for_next_time = $shippingInfoForNextTime;
            $userAddress->is_save_billing_info_for_next_time = $billingInfoForNextTime;
            $userAddress->added_user_id = $userId;
            $userAddress->update();

            DB::commit();

            $msg = __('shipping_address_updated_success', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::okStatusCode, Constants::successStatus);
        } catch (\Throwable $e) {

            DB::rollBack();
            $msg = __('core__api_db_error', [], $request->language_symbol);
            return responseMsgApi($e->getMessage(), Constants::badRequestStatusCode);
        }
    }

    public function editBillingAddressFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)) {
            $msg = __('shipping_and_billing__api_store_info_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        DB::beginTransaction();
        try {
            $id = $request->id;
            $userId = $request->login_user_id;

            $shippingInfoForNextTime = $request->is_save_shipping_info_for_next_time;
            $billingInfoForNextTime = $request->is_save_billing_info_for_next_time;

            if (!empty($shippingInfoForNextTime)) {
                UserAddress::where("added_user_id", $userId)
                    ->update([UserAddress::isSaveShippingInfoForNextTime => ps_constant::notSaveForNextTime]);
            }

            if (!empty($billingInfoForNextTime)) {
                UserAddress::where("added_user_id", $userId)
                    ->update([UserAddress::isSaveBillingInfoForNextTime => ps_constant::notSaveForNextTime]);
            }

            // update in psx_user_addresses table
            $userAddress = $this->getUserAddress(id: $id);
            $userAddress->user_id = $userId;
            $userAddress->first_name = $request->billing_first_name;
            $userAddress->last_name = $request->billing_last_name;
            $userAddress->email = $request->billing_email;
            $userAddress->phone_no = $request->billing_phone_no;
            $userAddress->address = $request->billing_address;
            $userAddress->country = $request->billing_country;
            $userAddress->state = $request->billing_state;
            $userAddress->city = $request->billing_city;
            $userAddress->postal_code = $request->billing_postal_code;
            $userAddress->is_save_shipping_info_for_next_time = $shippingInfoForNextTime;
            $userAddress->is_save_billing_info_for_next_time = $billingInfoForNextTime;
            $userAddress->added_user_id = $userId;
            $userAddress->update();

            DB::commit();

            $msg = __('billing_address_updated_success', [], $request->language_symbol);
            return responseMsgApi($msg, Constants::okStatusCode, Constants::successStatus);
        } catch (\Throwable $e) {

            DB::rollBack();
            $msg = __('core__api_db_error', [], $request->language_symbol);
            return responseMsgApi($e->getMessage(), Constants::badRequestStatusCode);
        }
    }
}
