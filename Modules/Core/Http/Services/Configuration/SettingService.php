<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Config\Cache\AppInfoCache;
use App\Http\Contracts\Configuration\SettingServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\Setting;
use Modules\Core\Http\Facades\PsCache;

class SettingService extends PsService implements SettingServiceInterface
{
    public function update($id, $settingData)
    {
        DB::beginTransaction();
        try {
            $this->updateSetting($id, $settingData);

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function get($id = null, $env = null)
    {
        return Setting::when($id, function ($query, $id) {
            $query->where(Setting::id, $id);
        })
            ->when($env, function ($query, $env) {
                $query->where(Setting::settingEnv, $env);
            })
            ->first();
    }

    //////////////////////////////////////////////////////
    /// Private Functions
    //////////////////////////////////////////////////////

    ///---------------------------------------------------
    /// Database
    ///---------------------------------------------------
    private function updateSetting($id, $settingData)
    {
        $setting = $this->get($id);
        $setting->update($settingData);

        return $setting;
    }
}
