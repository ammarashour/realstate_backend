<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        Schema::table('psx_vendors', function (Blueprint $table) {
            $table->boolean(Vendor::isUnlimited)->after(Vendor::ownerUserId)->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_vendors', function (Blueprint $table) {
            $table->dropColumn(Vendor::isUnlimited);
        });
    }
};
