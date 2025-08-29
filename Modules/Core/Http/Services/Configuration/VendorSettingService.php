<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\BeSettingCache;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Menu\MenuGroupService;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Configuration\SettingServiceInterface;
use App\Http\Contracts\Configuration\VendorSettingServiceInterface;
use App\Http\Contracts\Menu\CoreMenuServiceInterface;
use App\Http\Contracts\Menu\VendorMenuGroupServiceInterface;
use App\Http\Contracts\Menu\MenuGroupServiceInterface;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Http\Facades\PsCache;

class VendorSettingService extends PsService implements VendorSettingServiceInterface
{

    public function __construct(
        protected MenuGroupServiceInterface $menuGroupService,
        protected VendorMenuGroupServiceInterface $vendorMenuGroupService,
        protected CoreMenuServiceInterface $coreMenuService,
        protected BackendSettingServiceInterface $backendSettingService,
        protected SettingServiceInterface $settingService,
    ) {}


    public function update($id, $vendorSettingData)
    {
        try {
            DB::beginTransaction();
            
            // Update backend setting
            $backendSetting = $this->backendSettingService->get($id);
            $backendSetting->update($vendorSettingData);
            // Update vendor menu group
            $this->updateVendorMenuGroup($backendSetting['vendor_setting']);
            
            // Update core menu items visibility
            $menuIds = [70];
            $this->updateCoreMenuVisibility($menuIds, $backendSetting['vendor_setting']);
            
            // Hide package menu when subscription setting is FREE
            $this->updatePackageMenuVisibility($vendorSettingData['vendor_subscription']);
            
            // Update vendor subscription settings
            $this->updateVendorSubscriptionSetting($vendorSettingData, $backendSetting['vendor_setting']);
            
            PsCache::clear(BeSettingCache::BASE);
            PsCache::clear(AppInfoCache::BASE);
            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function updateVendorMenuGroup($vendorSetting)
    {
        $vendorMenuConds = ['group_name' => 'Vendor', 'id' => 5];
        $vendorMenuGroup = $this->menuGroupService->get(null, null, $vendorMenuConds);
        $this->menuGroupService->update($vendorMenuGroup['id'], ["is_show_on_menu" => $vendorSetting]);
    }

    private function updateCoreMenuVisibility($menuIds, $vendorSetting)
    {
        CoreMenu::whereIn('id', $menuIds)
        ->update(['is_show_on_menu' => $vendorSetting]);

        // $coreMenuData = [
        //     'is_show_on_menu' => $vendorSetting,
        //     'old_module_id' => $menuIds,
        //     'module_id' => $menuIds,
        // ];
        // $this->coreMenuService->update($menuIds, $coreMenuData);
    }

    private function updatePackageMenuVisibility($vendorSubscription)
    {
        $visibility = $this->prepareMenuVisibility($vendorSubscription);
        $vendorMenuGroup = $this->vendorMenuGroupService->get(3);
        $this->vendorMenuGroupService->update($vendorMenuGroup['id'], ["is_show_on_menu" => $visibility]);
    }

    private function prepareMenuVisibility($vendorSubscription)
    {
        return ($vendorSubscription === 'FREE') ? 0 : 1;
    }

    private function updateVendorSubscriptionSetting($vendorSettingData, $vendorSetting)
    {
        $vendorSubscriptionConfig = $this->settingService->get(null, Constants::VENDOR_SUBSCRIPTION_CONFIG);

        $selectedVendorSetting = [
            ["id" => $vendorSettingData['vendor_subscription']]
        ];

        $vendorSettingArray = [
            "subscription_plan" => $selectedVendorSetting,
            "notic_days" => $vendorSettingData['notic_days'],
            "vendor_checkout_setting" => !empty($vendorSetting) ? $vendorSettingData['vendor_checkout_setting'] : "0"
        ];

        $this->settingService->update($vendorSubscriptionConfig->id,  ["setting" => $vendorSettingArray]);
    }
}
