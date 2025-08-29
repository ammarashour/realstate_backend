<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class FrontendSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_frontend_settings')->insert([
            'map_key' => "AIzaSyCtBHbqTWRgh9O8veCOJNnCMG56lXTdGJw",
            'is_enable_video_setting' => 1,
            'show_user_profile' => 1,
            'no_filter_with_location_on_map' => 1,
            'price_format' => "#,##0.0",
            'enable_notification' => 1,
            'fcm_server_key' => "AAAAdas8rOs:APA91bEhLizx8g2RQpdxf6eWfZitLrriVUiAYUIzN6DKKSmXsfyXk6Oq79iMFDZADnEHsIdu-67rue1sss0QmFzKWnIvQi54eWvtvsJLQAlLS0ow4pO_3aQN9RUSiqsvPKSPcuKH_05d",
            'firebase_web_push_key_pair' => "BB25q3Hfb46baQzaD2geWF7_W529dt8Cz9VVbcti1vmO6C0-W3qKWVDHrvW3mCy8A6XSdZKOM8hZW7S7FOFqKuQ",
            'ad_client' => "ca-pub-8907881762519005",
            'ad_slot' => "9078562335",
            'copyright' => "Copyright @ PanaceaSoft 2022",
            'google_playstore_url' => "https: //play.google.com/store/apps/details?id=com.panaceasoft.flutterbuyandsell",
            'google_setting' => 1,
            'app_store_url' => "https: //www.apple.com/app-store/",
            'app_store_setting' => 1,
            'banner_src' => "chart.jpeg",
            'google_map' => 1,
            'open_street_map' => 0,
            'mile' => 8,
            'default_language' => "en",
            'selected_language' => "en",
            'promote_first_choice_day' => 7,
            'promote_second_choice_day' => 14,
            'promote_third_choice_day' => 30,
            'promote_fourth_choice_day' => 60,
            'gps_enable' => 1,
            'show_main_menu' => 1,
            'show_special_collections' =>1,
            'show_featured_items' =>1,
            'show_best_choice_slider' =>1,
            'frontend_version_no' => '',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
