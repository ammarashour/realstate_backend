<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BlogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_blogs')->insert([
            'name' => 'Burger Promotion 50% OFF',
            'description' => 'augue auctor eu. Sed aliquam eu eros mattis bibendum. Etiam porttitor interdum elit, in tincidunt erat feugiat sit amet. Sed vitae vestibulum magna. Nam vitae risus quis nisi dictum mattis in ut turpis. Sed consequat quam non congue semper. Quisque gravida mauris egestas, lobortis nisi ut, rutrum urna.',
            'location_city_id' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_blogs')->insert([
            'name' => 'Budget Motel In Town',
            'description' => 'Watermarking is only available using the GD/GD2 library. In addition, even though other libraries are supported, GD is required in order for the script to calculate the image properties. The image processing, however, will be performed with the library you specify.',
            'location_city_id' => '2',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_blogs')->insert([
            'name' => 'Good Ice-cream In Town',
            'description' => 'augue auctor eu. Sed aliquam eu eros mattis bibendum. Etiam porttitor interdum elit, in tincidunt erat feugiat sit amet. Sed vitae vestibulum magna. Nam vitae risus quis nisi dictum mattis in ut turpis. Sed consequat quam non congue semper. Quisque gravida mauris egestas, lobortis nisi ut, rutrum urna.',
            'location_city_id' => 'all',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_blogs')->insert([
            'name' => 'Best Hotel in Town',
            'description' => 'augue auctor eu. Sed aliquam eu eros mattis bibendum. Etiam porttitor interdum elit, in tincidunt erat feugiat sit amet. Sed vitae vestibulum magna. Nam vitae risus quis nisi dictum mattis in ut turpis. Sed consequat quam non congue semper. Quisque gravida mauris egestas, lobortis nisi ut, rutrum urna.',
            'location_city_id' => 'all',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_blogs')->insert([
            'name' => 'Coffee and Tea Buy 3 Get 1',
            'description' => 'augue auctor eu. Sed aliquam eu eros mattis bibendum. Etiam porttitor interdum elit, in tincidunt erat feugiat sit amet. Sed vitae vestibulum magna. Nam vitae risus quis nisi dictum mattis in ut turpis. Sed consequat quam non congue semper. Quisque gravida mauris egestas, lobortis nisi ut, rutrum urna.',
            'location_city_id' => '1',
            'status' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);
    }
}
