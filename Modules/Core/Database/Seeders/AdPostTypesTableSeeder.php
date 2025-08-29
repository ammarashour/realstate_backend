<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AdPostTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_ad_post_types')->insert([
            'key' => 'paid_item_first',
            'value' => 'Show Sponsored Ad at the top of Item List',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ad_post_types')->insert([
            'key' => 'bumps_ups_between',
            'value' => 'Bumps ups the Sponsored Ad Between Normal Ads',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ad_post_types')->insert([
            'key' => 'google_ads_between',
            'value' => 'Google Ad Between Normal Ads',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ad_post_types')->insert([
            'key' => 'bumps_and_google_ads_between',
            'value' => 'Sponsored Ad and Google Ad Between Normal Ads Alte',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_ad_post_types')->insert([
            'key' => 'normal_ads_only',
            'value' => 'Normal Ads Only',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
