<?php

namespace Modules\Core\Database\factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Configuration\MobileSetting;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MobileSettingFactory extends Factory
{
    protected $model = MobileSetting::class;

    public function definition()
    {
        return [
            "id" => $this->faker->numberBetween(0, 1000),
            "is_show_subcategory" => "1",
            "android_admob_banner_ad_unit_id" => $this->faker->slug(),
            "android_admob_native_unit_id" => $this->faker->slug(),
            "andorid_admob_interstitial_ad_unit_id" => $this->faker->slug(),
            "ios_admob_banner_ad_unit_id" => $this->faker->slug(),
            "ios_admob_native_ad_unit_id" => $this->faker->slug(),
            "ios_admob_interstitial_ad_unit_id" => $this->faker->slug(),
            "recent_search_keyword_limit" => $this->faker->numberBetween(1, 100),
            "data_config_data_source_type" => "FULL_CACHE",
            "data_config_day" => "7",
            "auto_play_interval" => "5",
            "loading_shimmer_item_count" => $this->faker->numberBetween(1, 10),
            "phone_list_count" => $this->faker->numberBetween(1, 3),
            "added_user_id" => User::factory(),
        ];
    }
}
