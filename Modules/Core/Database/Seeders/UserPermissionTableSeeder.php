<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_user_permissions')->insert([
            'user_id' => '1',
            'role_id' => '1',
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        for ($x = 2; $x <= 52; $x++) {
            DB::table('psx_user_permissions')->insert([
                'user_id' => $x,
                'role_id' => '2',
                'added_user_id' => '1',
                'added_date' => Carbon::now(),
            ]);
        }
    }
}
