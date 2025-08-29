<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Configuration;

use Modules\Core\Constants\Constants;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Configuration\Setting;

class SystemConfigApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $selcted_array = $this->getSetting();

        return [
            'lat' => checkAndGetValue($this, 'lat'),
            'lng' => checkAndGetValue($this, 'lng'),
            'is_approved_enable' => checkAndGetValue($this, 'is_approved_enable'),
            'is_sub_location' => checkAndGetValue($this, 'is_sub_location'),
            'is_thumb2x_3x_generate' => checkAndGetValue($this, 'is_thumb2x_3x_generate'),
            'is_sub_subscription' => checkAndGetValue($this, 'is_sub_subscription'),
            'is_paid_app' => checkAndGetValue($this, 'is_paid_app'),
            'is_promote_enable' => checkAndGetValue($this, 'is_promote_enable'),
            'is_block_user' => checkAndGetValue($this, 'is_block_user'),
            'max_img_upload_of_item' => checkAndGetValue($this, 'max_img_upload_of_item'),
            'ad_type' => checkAndGetValue($this, 'ad_type'),
            'promo_cell_interval_no' => checkAndGetValue($this, 'promo_cell_interval_no'),
            'one_day_per_price' => checkAndGetValue($this, 'one_day_per_price'),
            'selected_price_type' => (string) $selcted_array['selected_price_type']['id'],
            'selected_chat_type' => (string) $selcted_array['selected_chat_data']['id'],
            'soldout_feature_setting' => (string)$selcted_array['soldout_feature_setting'],
            'hide_price_setting' => (string)$selcted_array['hide_price_setting'],
        ];
    }

    ///////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////
    private function getSetting()
    {
        $setting = Setting::where('setting_env', Constants::SYSTEM_CONFIG )->first();
        return json_decode($setting->setting, true);
    }
}
