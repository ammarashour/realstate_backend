<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SystemConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_system_configs')->insert([
            'lat' => "16.879910",
            'lng' => "96.173248",
            'is_approved_enable' => 1,
            'is_sub_location' => 1,
            'is_thumb2x_3x_generate' => 1,
            'is_sub_subscription' => 1,
            'is_paid_app' => 0,
            'is_block_user' => 1,
            'max_img_upload_of_item' => 5,
            'ad_type' => 1,
            'promo_cell_interval_no' => 3,
            'one_day_per_price' => 5,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
