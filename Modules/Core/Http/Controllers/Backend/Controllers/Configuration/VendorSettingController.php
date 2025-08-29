<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Configuration;

use App\Config\Cache\LocalizationCache;
use Illuminate\Http\Request;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use Modules\Core\Entities\Configuration\Setting;
use Modules\Core\Http\Requests\Configuration\UpdateVendorSettingRequest;
use App\Http\Contracts\Configuration\SettingServiceInterface;
use App\Http\Contracts\Configuration\VendorSettingServiceInterface;
use App\Http\Contracts\Localization\VendorLanguageStringServiceInterface;
use Modules\Core\Entities\Configuration\BackendSetting;
use Modules\Core\Http\Facades\PsCache;

class VendorSettingController extends PsController
{
    private const parentPath = "vendor_setting/";
    private const editPath = self::parentPath . 'Edit';
    private const indexRoute = "vendor_setting.index";
    private const editRoute = "vendor_setting.edit";


    public function __construct(
        protected VendorSettingServiceInterface $vendorSettingService,
        protected BackendSettingServiceInterface $backendSettingService,
        protected SettingServiceInterface $settingService,
        protected VendorLanguageStringServiceInterface $vendorLanguageStringService
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(BackendSetting::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData();

        $dataArr['SettingPage'] = $request->query('page') ?? 0;

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorSettingRequest $request, $id)
    {
        try{

            $validateData = $request->validated();

            $this->vendorSettingService->update($id, $validateData);

            return redirectView(self::indexRoute);

        }catch(\Exception $e){
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $id);
        }
    }

    public function languageRefresh(Request $request)
    {
        $msg = "Vendor Language is refreshed Successfully";
        $languageId = $request->input('languageId');
        $this->vendorLanguageStringService->generateJsonFiles($languageId);
        PsCache::clear(LocalizationCache::BASE);
        return redirectView(self::indexRoute, $msg, "langSuccess", ['page=2']);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {
        $backend_setting = $this->backendSettingService->get();

        $vendor_subscription = $this->settingService->get(null, Constants::VENDOR_SUBSCRIPTION_CONFIG);

        return [
            'backend_setting' => $backend_setting,
            'vendor_subscription' => $vendor_subscription
        ];
    }
}
