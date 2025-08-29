<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Vendor\Vendor;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DynamicColumnVisibility::create([
            DynamicColumnVisibility::moduleName => Constants::vendor,
            DynamicColumnVisibility::key => Vendor::isUnlimited,
            DynamicColumnVisibility::isShow => 1,
            DynamicColumnVisibility::addedUserId => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
