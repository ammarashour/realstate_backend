<?php

use App\Config\Cache\BeSettingCache;
use App\Config\Cache\MbSettingCache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Configuration\BackendSetting;
use Modules\Core\Entities\Configuration\MobileSetting;
use Modules\Core\Http\Facades\PsCache;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $backendSettings = BackendSetting::first();
        if ($backendSettings) {
            $backendSettings->backend_version_no = "1.3.4";
            $backendSettings->update();
        }

        $mobileSetting = MobileSetting::first();
        if ($mobileSetting) {
            $mobileSetting->version_no = "1.3.4";
            $mobileSetting->update();
        }

        PsCache::clear(BeSettingCache::BASE);
        PsCache::clear(MbSettingCache::BASE);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
