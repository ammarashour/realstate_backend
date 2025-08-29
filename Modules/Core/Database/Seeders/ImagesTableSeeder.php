<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // For login backeground
        DB::table('psx_core_images')->insert([
            'img_parent_id' => 1,
            'img_type' => 'backend-login-image',
            'img_path' => 'backend_login_bg.png',
            'img_width' => '512',
            'img_height' => '512',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For backend logo
        DB::table('psx_core_images')->insert([
            'img_parent_id' => 1,
            'img_type' => 'backend-logo',
            'img_path' => 'backend_logo.png',
            'img_width' => '512',
            'img_height' => '512',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For fav icon
        DB::table('psx_core_images')->insert([
            'img_parent_id' => 1,
            'img_type' => 'fav-icon',
            'img_path' => 'favicon.ico',
            'img_width' => '512',
            'img_height' => '512',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For about
        DB::table('psx_core_images')->insert([
            'img_parent_id' => 1,
            'img_type' => 'about',
            'img_path' => 'about_wp.png',
            'img_width' => '512',
            'img_height' => '512',
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        // For Items
        // for ($x = 0; $x < 58; $x++) {
        //     DB::table('psx_core_images')->insert([
        //         'img_parent_id' => $x+1,
        //         'img_type' => 'item',
        //         'img_path' => 'item_'.($x+1).'.png',
        //         'img_width' => '512',
        //         'img_height' => '512',
        //         'added_user_id' => '1',
        //         'added_date'=>Carbon::now(),
        //     ]);
        // }

        // For Category
        // for ($x = 0; $x < 7; $x++) {
        //     DB::table('psx_core_images')->insert([
        //         'img_parent_id' => $x+1,
        //         'img_type' => 'category-cover',
        //         'img_path' => 'category_'.($x+1).'.png',
        //         'img_width' => '512',
        //         'img_height' => '512',
        //         'added_user_id' => '1',
        //         'added_date'=>Carbon::now(),
        //     ]);

        //     DB::table('psx_core_images')->insert([
        //         'img_parent_id' => $x+1,
        //         'img_type' => 'category-icon',
        //         'img_path' => 'category_icon_'.($x+1).'.png',
        //         'img_width' => '512',
        //         'img_height' => '512',
        //         'added_user_id' => '1',
        //         'added_date'=>Carbon::now(),
        //     ]);
        // }

        // For Subcategory
        // for ($x = 0; $x < 24; $x++) {
        //     DB::table('psx_core_images')->insert([
        //         'img_parent_id' => $x+1,
        //         'img_type' => 'subcategory-cover',
        //         'img_path' => 'subcategory_'.($x+1).'.png',
        //         'img_width' => '512',
        //         'img_height' => '512',
        //         'added_user_id' => '1',
        //         'added_date'=>Carbon::now(),
        //     ]);

        //     DB::table('psx_core_images')->insert([
        //         'img_parent_id' => $x+1,
        //         'img_type' => 'subcategory-icon',
        //         'img_path' => 'subcategory_icon_'.($x+1).'.png',
        //         'img_width' => '512',
        //         'img_height' => '512',
        //         'added_user_id' => '1',
        //         'added_date'=>Carbon::now(),
        //     ]);
        // }
    }
}
