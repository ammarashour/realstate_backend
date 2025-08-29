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
        Schema::table('psx_customize_ui', function (Blueprint $table) {
            $table->after("data_type", function ($table){

                $table->integer("table_id");
                $table->foreignId("project_id");
                $table->string('project_name');
                $table->string("base_module_name");
                $table->tinyInteger("is_include_in_hideshow")->default(0);
                $table->tinyInteger("is_show")->default(0);
                $table->tinyInteger("is_core_field")->default(0);
                $table->tinyInteger("permission_for_enable_disable");
                $table->tinyInteger("permission_for_delete");
                $table->tinyInteger("permission_for_mandatory");
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_customize_ui', function (Blueprint $table) {
            $table->dropColumn(['base_module_name', 'table_id','is_core_field', 'is_include_in_hideshow', 'is_show', "permission_for_enable_disable", "permission_for_delete", "permission_for_mandatory", "project_id", "project_name"]);
        });
    }
};
