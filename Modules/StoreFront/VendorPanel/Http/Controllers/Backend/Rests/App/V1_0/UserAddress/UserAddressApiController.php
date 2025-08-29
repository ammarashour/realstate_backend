<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Rests\App\V1_0\UserAddress;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\UserAddressService;

class UserAddressApiController extends Controller
{
    protected $userAddressService, $translator;

    public function __construct(Translator $translator, UserAddressService $userAddressService)
    {
        $this->userAddressService = $userAddressService;
        $this->translator = $translator;
    }

    public function getAllShippingForUser(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'login_user_id' => 'required|exists:users,id',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        $shippingInfos = $this->userAddressService->getAllShippingForUserFromApi($request);
        return $shippingInfos;
    }

    public function getAllBillingForUser(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'login_user_id' => 'required|exists:users,id',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        $billingInfos = $this->userAddressService->getAllBillingForUserFromApi($request);
        return $billingInfos;
    }

    public function getDefaultShippingAndBillingForUser(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'login_user_id' => 'required|exists:users,id',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        $defaultShippingAndBillingInfo = $this->userAddressService->getDefaultShippingAndBillingForUserFromApi($request);
        return $defaultShippingAndBillingInfo;
    }

    public function addNewShippingAddress(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'shipping_first_name' => 'required',
            'shipping_last_name' => 'required',
            'shipping_email' => 'required|email',
            'shipping_phone_no' =>'required',
            'shipping_address' => 'required',
            'shipping_country' => 'required',
            'shipping_state' => 'required',
            'shipping_city' => 'required',
            'shipping_postal_code' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->userAddressService->addNewShippingAddressFromApi($request);
    }

    public function addNewBillingAddress(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_phone_no' =>'required',
            'billing_address' => 'required',
            'billing_country' => 'required',
            'billing_state' => 'required',
            'billing_city' => 'required',
            'billing_postal_code' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->userAddressService->addNewBillingAddressFromApi($request);
    }

    public function editShippingAddress(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:psx_user_addresses,id',
            'shipping_first_name' => 'required',
            'shipping_last_name' => 'required',
            'shipping_email' => 'required|email',
            'shipping_phone_no' =>'required',
            'shipping_address' => 'required',
            'shipping_country' => 'required',
            'shipping_state' => 'required',
            'shipping_city' => 'required',
            'shipping_postal_code' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->userAddressService->editShippingAddressFromApi($request);
    }

    public function editBillingAddress(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:psx_user_addresses,id',
            'billing_first_name' => 'required',
            'billing_last_name' => 'required',
            'billing_email' => 'required|email',
            'billing_phone_no' =>'required',
            'billing_address' => 'required',
            'billing_country' => 'required',
            'billing_state' => 'required',
            'billing_city' => 'required',
            'billing_postal_code' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->userAddressService->editBillingAddressFromApi($request);
    }
}
