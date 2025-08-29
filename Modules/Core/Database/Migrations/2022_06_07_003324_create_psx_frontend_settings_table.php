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
        //        $module->title = 'frontend_settings';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_frontend_settings', function (Blueprint $table) {
            $table->id();
            $table->string('map_key')->nullable();
            $table->string('google_playstore_url')->nullable();
            $table->string('app_store_url')->nullable();
            $table->boolean('gps_enable')->nullable();
            $table->boolean('show_main_menu')->nullable();
            $table->boolean('show_special_collections')->nullable();
            $table->boolean('show_featured_items')->nullable();
            $table->boolean('show_best_choice_slider')->nullable();
            $table->string('fcm_server_key')->nullable();
            $table->string('firebase_web_push_key_pair')->nullable();
            $table->string('ad_client')->nullable();
            $table->string('ad_slot')->nullable();
            $table->string('copyright')->nullable();
            $table->string('price_format')->nullable();
            $table->string('banner_src')->nullable();
            $table->string('mile')->nullable();
            $table->boolean('is_enable_video_setting')->nullable();
            $table->boolean('show_user_profile')->nullable();
            $table->boolean('no_filter_with_location_on_map')->nullable();
            $table->boolean('enable_notification')->nullable();
            $table->boolean('google_setting')->nullable();
            $table->boolean('app_store_setting')->nullable();
            $table->boolean('google_map')->nullable();
            $table->boolean('open_street_map')->nullable();
            $table->string('default_language')->nullable();
            $table->string('selected_language')->nullable(); //note exclude_language
            $table->string('promote_first_choice_day')->nullable();
            $table->string('promote_second_choice_day')->nullable();
            $table->string('promote_third_choice_day')->nullable();
            $table->string('promote_fourth_choice_day')->nullable();
            $table->string('frontend_version_no')->nullable();
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
        Schema::dropIfExists('psx_frontend_settings');
    }
};
