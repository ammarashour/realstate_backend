<?php

namespace Modules\Core\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@ps.com',
            'password' => Hash::make('admin'),
            'user_is_sys_admin' => 1,
            'role_id' => 1,
            'added_user_id' => '1',
            'overall_rating' => 0,
            'verify_types' => 1,
            'added_date_timestamp' => strtotime(Carbon::now()),
            'added_date'=>Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'user_is_sys_admin' => 0,
            'role_id' => '2',
            'added_user_id' => '1',
            'overall_rating' => 0,
            'verify_types' => 1,
            'added_date_timestamp' => strtotime(Carbon::now()),
            'added_date'=>Carbon::now(),
        ]);
    }
}
