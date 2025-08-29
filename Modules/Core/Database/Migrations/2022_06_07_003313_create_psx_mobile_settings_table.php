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
        //        $module->title = 'mobile_settings';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_mobile_settings', function (Blueprint $table) {
            $table->id();
            $table->string('apple_appstore_url')->nullable();
            $table->string('ios_appstore_id')->nullable();
            $table->string('google_playstore_url')->nullable();
            $table->boolean('is_show_admob')->nullable();
            $table->boolean('is_show_item_video')->nullable();
            $table->string('fb_key')->nullable();
            $table->string('date_format')->nullable();
            $table->string('price_format')->nullable();
            $table->string('default_razor_currency')->nullable();
            $table->boolean('is_razor_support_multi_currency')->nullable();
            $table->boolean('is_show_subcategory')->nullable();
            $table->boolean('show_phone_login')->nullable();
            $table->boolean('show_google_login')->nullable();
            $table->boolean('show_facebook_login')->nullable();
            //$table->boolean('is_show_token_id')->nullable();
            $table->boolean('is_use_thumbnail_as_placeholder')->nullable();
            $table->boolean('is_use_google_map')->nullable();
            $table->integer('item_detail_view_count_for_ads')->nullable();
            $table->boolean('is_show_ads_in_item_detail')->nullable();
            $table->integer('after_item_count_admob_once')->nullable();
            $table->integer('blue_mark_size')->nullable();
            $table->integer('block_item_loading_limit')->nullable();
            $table->integer('follower_item_loading_limit')->nullable();
            $table->integer('block_slider_loading_limit')->nullable();
            $table->integer('featured_item_loading_limit')->nullable();
            $table->integer('popular_item_loading_limit')->nullable();
            $table->integer('recent_item_loading_limit')->nullable();
            $table->integer('category_loading_limit')->nullable();
            $table->integer('default_loading_limit')->nullable();
            $table->integer('discount_item_loading_limit')->nullable();
            $table->string('mile')->nullable();
            $table->integer('video_duration')->nullable();
            $table->boolean('is_show_owner_info')->nullable();
            $table->boolean('is_force_login')->nullable();
            $table->boolean('is_language_config')->nullable();
            $table->boolean('no_filter_with_location_on_map')->nullable();
            $table->integer('chat_image_size')->nullable();
            $table->integer('profile_image_size')->nullable();
            $table->integer('upload_image_size')->nullable();
            $table->string('promote_first_choice_day')->nullable();
            $table->string('promote_second_choice_day')->nullable();
            $table->string('promote_third_choice_day')->nullable();
            $table->string('promote_fourth_choice_day')->nullable();
            $table->float('lat', 10, 6)->nullable();
            $table->float('lng', 10, 6)->nullable();
            $table->integer('collection_item_loading_limit')->nullable();
            $table->integer('shop_loading_limit')->nullable();
            $table->boolean('show_main_menu')->nullable();
            $table->boolean('show_special_collections')->nullable();
            $table->boolean('show_featured_items')->nullable();
            $table->boolean('show_best_choice_slider')->nullable();
            $table->string('default_flutter_wave_currency')->nullable();
            $table->string('default_order_time')->nullable();
            $table->integer('trending_item_loading_limit')->nullable();
            $table->string('version_no')->nullable(); // (main app)
            $table->string('version_force_update')->nullable(); // (main app)
            $table->string('version_title')->nullable(); // (main app)
            $table->string('version_message')->nullable(); // (main app)
            $table->string('version_need_clear_data')->nullable(); // (main app)
            $table->string('deli_boy_version_no')->nullable(); // (deli app)
            $table->string('deli_boy_version_force_update')->nullable(); // (deli app)
            $table->string('deli_boy_version_title')->nullable(); // (deli app)
            $table->string('deli_boy_version_message')->nullable(); // (deli app)
            $table->string('deli_boy_version_need_clear_data')->nullable(); // (deli app)
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
        Schema::dropIfExists('psx_mobile_settings');
    }
};
