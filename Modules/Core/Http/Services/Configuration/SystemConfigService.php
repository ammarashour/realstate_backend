<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\SystemConfigCache;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Configuration\SystemConfigServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\SystemConfig;
use Modules\Core\Http\Facades\PsCache;

class SystemConfigService extends PsService implements SystemConfigServiceInterface
{
    public function __construct(protected MobileSettingServiceInterface $mobileSettingService,
        protected SettingService $settingService) {}

    public function update($id = null, $systemConfigData, $adsTxtFile = null, $mobileSettingId = null, $mobileSettingData = null)
    {
        DB::beginTransaction();
        try {
            // update system config
            $this->updateSystemConfig($id, $systemConfigData);

            // update mobile settings
            $this->mobileSettingService->update($mobileSettingId, $mobileSettingData);

            // update setting
            $systemConfig = $this->settingService->get(id: null, env: Constants::SYSTEM_CONFIG);
            $settingData = $this->prepareSettingData($systemConfig, $systemConfigData);
            $this->settingService->update($systemConfig->id, $settingData);

            // save ads text file
            $this->saveAdsTxtFile($adsTxtFile);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

            PsCache::clear(SystemConfigCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function get($id = null, $relation = null)
    {
        $param = [$id, $relation];

        return PsCache::remember([SystemConfigCache::BASE], SystemConfigCache::GET_EXPIRY, $param,
            function() use($id, $relation) {
                return SystemConfig::when($id, function ($query, $id) {
                        $query->where(SystemConfig::id, $id);
                    })
                    ->when($relation, function ($query, $relation) {
                        $query->with($relation);
                    })
                    ->first();
            });
    }

    ////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------
    // Data Prepare
    //-------------------------------------------------------------
    private function prepareSettingData($systemConfig, $systemConfigData)
    {
        $selected_price_data = [
            'id' => $systemConfigData['selected_price_type'],
        ];

        $selected_chat_data = [
            'id' => $systemConfigData['selected_chat_type'],
        ];

        $setting = json_decode($systemConfig->setting, true);
        $setting['selected_price_type'] = $selected_price_data;
        $setting['selected_chat_data'] = $selected_chat_data;
        $setting['soldout_feature_setting'] = $systemConfigData['soldout_feature_setting'];
        $setting['hide_price_setting'] = $systemConfigData['hide_price_setting'];
        $setting['display_ads_id'] = $systemConfigData['display_ads_id'];
        $setting['ads_client'] = $systemConfigData['ads_client'];
        $setting['is_display_google_adsense'] = $systemConfigData['is_display_google_adsense'];

        return [
            'setting' => $setting,
        ];
    }

    //-------------------------------------------------------------
    // Database
    //-------------------------------------------------------------
    private function updateSystemConfig($id, $systemConfigData)
    {
        $systemConfig = $this->get($id);
        $systemConfig->updated_user_id = Auth::id();
        $systemConfig->update($systemConfigData);

        return $systemConfig;
    }

    ///--------------------------------------------------------------------
    /// Others
    ///--------------------------------------------------------------------
    private function saveAdsTxtFile($adsTxtFile)
    {
        if ($adsTxtFile !== null) {
            $newFileName = ps_constant::adsTxtFileNameForAdsense;

            $filePath = base_path('/');

            $adsTxtFile->move($filePath, $newFileName);
        }
    }
}
