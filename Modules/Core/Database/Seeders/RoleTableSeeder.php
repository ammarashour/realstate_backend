<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('psx_roles')->insert([
            'name' => 'Super Admin',
            'status' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_roles')->insert([
            'name' => 'Registered User',
            'status' => 1,
            'added_user_id' => '1',
            'added_date' => Carbon::now(),
        ]);

        DB::table('psx_roles')->insert([
            'name' => 'Deliboy',
            'status' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_roles')->insert([
            'name' => 'Administration',
            'status' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_roles')->insert([
            'name' => 'Manager',
            'status' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_roles')->insert([
            'name' => 'Sale Manager',
            'status' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);

        DB::table('psx_roles')->insert([
            'name' => 'Staff',
            'status' => 1,
            'added_user_id' => '1',
            'added_date'=>Carbon::now(),
        ]);
    }
}
