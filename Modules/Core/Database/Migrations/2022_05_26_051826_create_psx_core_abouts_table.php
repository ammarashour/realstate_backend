<?php

use Carbon\Carbon;
use Modules\Core\Entities\Menu\Module;
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
        // add in module table for authorization with policy
        //        $module = new Module();
        //        $module->title = 'core_abouts';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_core_abouts', function (Blueprint $table) {
            $table->id();
            $table->string('about_title')->nullable();
            $table->longtext('about_description')->nullable();
            $table->string('about_email')->nullable();
            $table->string('about_phone')->nullable();
            $table->string('about_address')->nullable();
            $table->string('about_website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('google_plus')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('twitter')->nullable();
            $table->string('GDPR')->nullable();
            $table->integer('upload_point')->nullable();
            $table->longText('safety_tips')->nullable();
            $table->longText('faq_pages')->nullable();
            $table->longText('terms_and_conditions')->nullable();
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
        Schema::dropIfExists('psx_core_abouts');
    }
};
