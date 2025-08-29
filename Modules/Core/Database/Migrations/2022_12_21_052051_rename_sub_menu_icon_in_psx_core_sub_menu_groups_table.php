<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_core_sub_menu_groups', function (Blueprint $table) {
            $table->renameColumn('sub_menu_icon', 'icon_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_core_sub_menu_groups', function (Blueprint $table) {
            $table->renameColumn('icon_id', 'sub_menu_icon');
        });
    }
};
