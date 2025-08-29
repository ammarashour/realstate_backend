<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class MobileSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_mobile_settings')->insert([
            'apple_appstore_url' => "https: //www.apple.com/app-store",
            'ios_appstore_id' => "789135275",
            'google_playstore_url' => "https: //play.google.com/store/apps",
            'is_show_admob' => 1,
            'fb_key' => "126556525430166",
            'date_format' => "dd-MM-yyyy",
            'price_format' => ",##0.00",
            'default_razor_currency' => "",
            'is_razor_support_multi_currency' => 1,
            'is_show_subcategory' => 1,
            'is_show_discount' => 1,
            'show_phone_login' => 1,
            'show_google_login' => 1,
            'show_apple_login' => 1,
            'show_facebook_login' => 1,
            //'is_show_token_id' => 1,
            'is_use_google_map' => 1,
            'is_show_item_video' => 1,
            'item_detail_view_count_for_ads' => 1,
            'is_show_ads_in_item_detail' => 1,
            'after_item_count_admob_once' => 1,
            'is_use_thumbnail_as_placeholder' => 1,
            'blue_mark_size' => "15",
            'block_item_loading_limit' => 30,
            'follower_item_loading_limit' => 30,
            'block_slider_loading_limit' => 30,
            'featured_item_loading_limit' => 30,
            'popular_item_loading_limit' => 30,
            'recent_item_loading_limit' => 30,
            'category_loading_limit' => 30,
            'default_loading_limit' => 30,
            'discount_item_loading_limit' => 30,
            'mile' => "8",
            'video_duration' => "60000",
            'is_show_owner_info' => 1,
            'is_force_login' => 1,
            'is_language_config' => 1,
            'no_filter_with_location_on_map' => 1,
            'chat_image_size' => "650",
            'profile_image_size' => "512",
            'upload_image_size' => "1024",
            'promote_first_choice_day' => "7",
            'promote_second_choice_day' => "14",
            'promote_third_choice_day' => "30",
            'promote_fourth_choice_day' => "60",
            'lat' => "16.879910",
            'lng' => "96.173248",
            'collection_item_loading_limit' => 30,
            'shop_loading_limit' => 30,
            'trending_item_loading_limit' => 30,
            'show_main_menu' => 1,
            'show_special_collections' => 1,
            'show_featured_items' => 1,
            'show_best_choice_slider' => 1,
            'default_flutter_wave_currency' => "",
            'default_order_time' => "1",
            'version_no' => "",
            'version_force_update' => "",
            'version_title' => "",
            'version_message' => "",
            'version_need_clear_data' => "",
            'deli_boy_version_no' => "",
            'deli_boy_version_force_update' => "",
            'deli_boy_version_title' => "",
            'deli_boy_version_message' => "",
            'deli_boy_version_need_clear_data' => "",
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
