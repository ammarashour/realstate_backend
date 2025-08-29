<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Config\Cache\AppInfoCache;
use App\Config\Cache\MbSettingCache;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\MobileSetting;
use Modules\Core\Http\Facades\PsCache;
use App\Http\Contracts\Configuration\ColorServiceInterface;

class MobileSettingService extends PsService implements MobileSettingServiceInterface
{
    public function __construct(protected ColorServiceInterface $colorService) {}

    // not using
    public function save($mobileSettingData, $mobileColors = [])
    {
        DB::beginTransaction();
        try {
            // save Mobile settings
            $this->saveMobileSetting($mobileSettingData);

            // update Mobile color
            foreach ($mobileColors as $mobileColor) {
                $mobile_color = $this->perpareMobileColor($mobileColor);
                $this->colorService->update($mobile_color['id'], $mobile_color);
            }

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

            PsCache::clear(MbSettingCache::BASE);
        } catch (\Throwable $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function update($id = null, $mobileSettingData, $mobileColors = [])
    {
        DB::beginTransaction();
        try {
            // update Mobile settings
            $this->updateMobileSetting($id, $mobileSettingData);

            // update Mobile color
            foreach ($mobileColors as $mobileColor) {
                $mobile_color = $this->perpareMobileColor($mobileColor);
                $this->colorService->update($mobile_color['id'], $mobile_color);
            }

            PsCache::clear(AppInfoCache::BASE);

            DB::commit();

            PsCache::clear(MbSettingCache::BASE);
        } catch (\Throwable $e) {
            DB::rollback();

            throw $e;
        }
    }

    public function get($id = null, $relation = null)
    {
        $param = [$id, $relation];

        return PsCache::remember([MbSettingCache::BASE], MbSettingCache::GET_EXPIRY, $param,
            function() use($id, $relation) {
                return MobileSetting::when($id, function ($query, $id) {
                        $query->where(MobileSetting::id, $id);
                    })
                    ->when($relation, function ($query, $relation) {
                        $query->with($relation);
                    })
                    ->first();
            });
    }

    //////////////////////////////////////////////////////////////////
    /// Private Function
    //////////////////////////////////////////////////////////////////

    ///---------------------------------------------------------------
    /// Data Preparation
    ///---------------------------------------------------------------
    private function perpareMobileColor($mobileColor)
    {
        return [
            'id' => $mobileColor->id,
            'key' => $mobileColor->key,
            'value' => $mobileColor->value,
            'title' => $mobileColor->title,
            'fe_color' => $mobileColor->fe_color,
            'mb_color' => $mobileColor->mb_color,
        ];
    }

    ///---------------------------------------------------------------
    /// Database
    ///---------------------------------------------------------------
    private function saveMobileSetting($mobileSettingData)
    {
        $mobileSetting = new MobileSetting;
        $mobileSetting->fill($mobileSettingData);
        $mobileSetting->added_user_id = Auth::id();
        $mobileSetting->save();

        return $mobileSetting;
    }

    private function updateMobileSetting($id, $mobileSettingData)
    {
        $mobileSetting = $this->get($id);
        $mobileSetting->updated_user_id = Auth::id();
        $mobileSetting->update($mobileSettingData);

        return $mobileSetting;
    }
}
