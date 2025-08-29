<?php

namespace Modules\Core\Transformers\Backend\Model\Configuration;

use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Configuration\Setting;

class SystemConfigWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        [$ref_array, $selcted_array] = $this->getSetting();

        $adsTxtFileName = $this->getAdsTxtFile();

        return [
            'id' => (string) $this->id,
            'lat' => (string) $this->lat,
            'lng' => (string) $this->lng,
            'is_approved_enable' => (string) $this->is_approved_enable,
            'is_sub_location' => (string) $this->is_sub_location,
            'is_thumb2x_3x_generate' => (string) $this->is_thumb2x_3x_generate,
            'is_sub_subscription' => (string) $this->is_sub_subscription,
            'is_paid_app' => (string) $this->is_paid_app,
            'free_ad_post_count' => (string) $this->free_ad_post_count,
            'is_promote_enable' => (string) $this->is_promote_enable,
            'is_block_user' => (string) $this->is_block_user,
            'selected_price_type' => (string) $selcted_array['selected_price_type']['id'],
            'selected_chat_type' => (string) $selcted_array['selected_chat_data']['id'],
            'soldout_feature_setting' => (string) $selcted_array['soldout_feature_setting'],
            'hide_price_setting' => (string) $selcted_array['hide_price_setting'],
            'max_img_upload_of_item' => (string) $this->max_img_upload_of_item,
            'ad_type' => (string) $this->ad_type,
            'promo_cell_interval_no' => (string) $this->promo_cell_interval_no,
            'one_day_per_price' => (string) $this->one_day_per_price,
            'added_date' => (string) $this->added_date,
            'added_user_id' => (string) $this->added_user_id,
            'added_user@@name' => (string) $this->owner->name,
            'updated_user_id' => (string) $this->updated_user_id,
            'updated_user@@name' => !empty($this->editor) ? $this->editor->name : '',
            'updated_flag' => (string) $this->updated_flag,
            'authorizations' => $this->authorization,
            'item_price_types' => $ref_array['price_type'],
            'item_chat_types' => $ref_array['item_chat_type'],
            'display_ads_id' => !empty($selcted_array['display_ads_id']) ? $selcted_array['display_ads_id'] : '' ,
            'ads_client' => !empty($selcted_array['ads_client']) ? $selcted_array['ads_client'] : '',
            'is_display_google_adsense' => !empty($selcted_array['is_display_google_adsense']) ? $selcted_array['is_display_google_adsense'] : '',
            'ads_txt_file_name' => $adsTxtFileName
        ];
    }

    //////////////////////////////////////////////////////////////
    /// Private Functions
    //////////////////////////////////////////////////////////////
    private function getSetting()
    {
        $conds = [
            'setting_env' => Constants::SYSTEM_CONFIG
        ];
        $setting = Setting::where($conds)->first();

        $ref_array = json_decode($setting->ref_selection, true);
        $selcted_array = json_decode($setting->setting, true);

        return [
            $ref_array,
            $selcted_array
        ];
    }

    private function getAdsTxtFile()
    {
        $file = ps_constant::adsTxtFileNameForAdsense;
        $filePath = base_path('/'.$file);
        if(file_exists($filePath)){
            return $file;
        }
        return null;
    }
}
