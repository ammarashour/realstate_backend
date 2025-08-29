<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Menu\Module;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psx_customize_ui', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('placeholder')->nullable();
            $table->string('ui_type_id')->nullable();
            $table->string('core_keys_id');
            $table->tinyInteger('mandatory');
            $table->tinyInteger('enable');
            $table->tinyInteger('is_delete')->default(0);
            $table->string('module_name');
            $table->string('data_type')->nullable();
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
        Schema::dropIfExists('psx_customize_ui');
    }
};
