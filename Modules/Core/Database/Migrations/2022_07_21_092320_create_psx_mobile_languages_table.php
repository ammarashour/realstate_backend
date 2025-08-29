<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Menu\Module;
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

        // add in module table for authorization with policy
        //        $module = new Module();
        //        $module->title = 'mobile_languages';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_mobile_languages', function (Blueprint $table) {
            $table->id();
            $table->string('symbol'); // eg : "en"
            $table->string("language_code"); // eg : "en"
            $table->string("country_code"); // eg : "US"
            $table->string('name'); // eg : "English"
            $table->string('code'); // eg : 1552296328 ( timestamp )
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('enable')->default(0);
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
        Schema::dropIfExists('psx_mobile_languages');
    }
};
