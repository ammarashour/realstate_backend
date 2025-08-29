<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // for ($x = 1; $x < 61; $x++) {
        //     DB::table('psx_role_permissions')->insert([
        //         'role_id' => '1',
        //         'module_id' => $x,
        //         'permission_id' => '1,2,3,4',
        //         'added_user_id' => '1',
        //         'added_date' => Carbon::now(),
        //     ]);

        //     DB::table('psx_role_permissions')->insert([
        //         'role_id' => '2',
        //         'module_id' => $x,
        //         'permission_id' => '1,2,3,4',
        //         'added_user_id' => '1',
        //         'added_date' => Carbon::now(),
        //     ]);
        // }
    }
}
