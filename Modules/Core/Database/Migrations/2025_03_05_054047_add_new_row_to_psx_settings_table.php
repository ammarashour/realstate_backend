<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $backendSetting = DB::table('psx_backend_settings')->first();
        $androidPackage = $backendSetting->dyn_link_package_name;
        $appleId = $backendSetting->ios_appstore_id;

        DB::table('psx_settings')->insert([
            'setting_env' => 'dynamic_link_config',
            'setting' => json_encode([
                'default_dynamic_link' => ['id' => 'FIREBASE'],
                'scheme_name' => "$androidPackage.app",
                'android_package' => $androidPackage,
                'apple_id' => $appleId
            ]),
            'ref_selection' => json_encode([
                'default_dynamic_link' => [
                    ['id' => 'FIREBASE', 'value' => 'Firebase'],
                    ['id' => 'PSX_DYNAMIC_LINK', 'value' => 'PSX Dynamic Link']
                ]
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('psx_settings')->where('setting_env', 'dynamic_link_config')->delete();
    }
};
