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
        Schema::create('psx_check_version_updates', function (Blueprint $table) {
            $table->id();
            $table->string("source_code_version_number");
            $table->integer("source_code_version_code");
            $table->string("backend_language_version_number");
            $table->integer("backend_language_version_code");
            $table->string("frontend_language_version_number");
            $table->integer("frontend_language_version_code");
            $table->string("mobile_language_version_number");
            $table->integer("mobile_language_version_code");
            $table->string("field_table_version_number");
            $table->integer("field_table_version_code");
            $table->timestamp('added_date');
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_check_version_updates');
    }
};
