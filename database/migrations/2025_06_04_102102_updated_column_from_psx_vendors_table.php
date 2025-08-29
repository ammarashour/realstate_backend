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
        DB::statement("ALTER TABLE psx_vendors MODIFY added_date DATETIME DEFAULT CURRENT_TIMESTAMP");
        DB::statement("ALTER TABLE psx_vendors MODIFY updated_date DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_vendors', function (Blueprint $table) {
            //
        });
    }
};
