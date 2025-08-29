<?php

// namespace Modules\Core\Http\Controllers\Backend\Controllers\Vendor;

// use App\Config\Cache\LocalizationCache;
// use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
// use App\Http\Contracts\Configuration\SettingServiceInterface;
// use App\Http\Contracts\Configuration\VendorSettingServiceInterface;
// use App\Http\Contracts\Localization\LanguageServiceInterface;
// use App\Http\Controllers\PsController;
// use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
// use Modules\Core\Constants\Constants;
// use Modules\Core\Entities\Configuration\BackendSetting;
// use Modules\Core\Http\Facades\PsCache;
// use Modules\Core\Http\Requests\Configuration\UpdateVendorSettingRequest;

/**
 * @deprecated
 */
// class VendorSettingController extends PsController
// {
//     const parentPath = "vendor_setting/";
//     const editPath = self::parentPath . 'Edit';
//     const indexRoute = "vendor_setting.index";


//     public function __construct(
//         protected VendorSettingServiceInterface $vendorSettingService,
//         protected BackendSettingServiceInterface $backendSettingService,
//         protected SettingServiceInterface $settingService,
//         protected LanguageServiceInterface $languageService
//     )
//     {
//         parent::__construct();
//     }

//     public function index(Request $request)
//     {
//         $this->handlePermissionWithModel(BackendSetting::class, Constants::viewAnyAbility);

//         $dataArr = $this->prepareIndexData();

//         return renderView(self::editPath, $dataArr);
//     }

//     public function update(UpdateVendorSettingRequest $request, $id)
//     {
//         // validation end
//         if(isset($request->vendor_setting)){
//             $updateObj = new \stdClass();
//             $updateObj->vendor_setting = $request->vendor_setting;
//             $updateObj->vendor_subscription = $request->vendor_subscription;
//             $updateObj->notic_days = $request->notic_days;
//             $updateObj->vendor_checkout_setting = $request->vendor_checkout_setting;
//             $updateObj->id = $id;
//         }else{
//             return redirect()->back();
//         }

//         $backend_setting = $this->vendorSettingService->update($id,$updateObj);

//         // if have error
//         if (isset($backend_setting['error'])){
//             $msg = $backend_setting['error'];
//             // dd($backend_setting['error']);
//             return redirectView(self::indexRoute, $msg, Constants::danger);
//         }


//         return redirect()->back();
//     }

//     public function languageRefresh(Request $request){
//         $languageId = $request->input('languageId');
//         $msg = "Vendor Language is refreshed Successfully";
//         generateVendorLanguages($languageId);
//         PsCache::clear(LocalizationCache::BASE);
//         return redirectView(self::indexRoute, $msg, "langSuccess");
//     }

//     private function prepareIndexData()
//     {
//         $backend_setting = $this->backendSettingService->get();
//         $vendor_subscription = $this->settingService->get(env: Constants::VENDOR_SUBSCRIPTION_CONFIG);
//         $languages = $this->languageService->getAll();
//         $dataArr = [
//             'backend_setting' => $backend_setting,
//             'vendor_subscription' => $vendor_subscription,
//             'languages' => $languages
//         ];
//         return $dataArr;
//     }

// }
