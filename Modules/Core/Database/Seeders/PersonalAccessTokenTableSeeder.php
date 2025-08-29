<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonalAccessTokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name' => 'userMobileToken',
            'token' => '16cdda84e3a5b3648905e3880a96a89a6250d7e52cfca3e96b296e3ec009405c',
            'abilities' => '["userMobileToken"]',
            'last_used_at' => Carbon::now(),
        ]);

        DB::table('psx_personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name' => 'adminMobileToken',
            'token' => '4fdea92fdcdc888f8ea3434060c63e55926dc5190bb20daea0404d92c1ac05f8',
            'abilities' => '["userMobileToken"]',
            'last_used_at' => Carbon::now(),
        ]);

        DB::table('psx_personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name' => 'userWebsiteToken',
            'token' => '26f4c43103904284775cf4afec8e4473291335c3f23ca4a122482960b2bbce17',
            'abilities' => '["userMobileToken"]',
            'last_used_at' => Carbon::now(),
        ]);

        DB::table('psx_personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => 1,
            'name' => 'deliboyMobileToken',
            'token' => 'abbb713c68301c1c97512eddc36d03a71a1fafd81173f35f8a85d94509f0d7a4',
            'abilities' => '["userMobileToken"]',
            'last_used_at' => Carbon::now(),
        ]);

    }
}
