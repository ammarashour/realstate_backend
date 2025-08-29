<?php

use App\Config\Cache\MbSettingCache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
        $mobileSetting = MobileSetting::first();
        if ($mobileSetting) {
            $mobileSetting->category_loading_limit = "100";
            $mobileSetting->update();
        }

        PsCache::clear(MbSettingCache::BASE);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};
