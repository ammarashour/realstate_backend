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
        Schema::table('psx_mobile_settings', function (Blueprint $table) {
            $table->after("version_need_clear_data", function ($table){
                $table->String('android_admob_banner_ad_unit_id');
                $table->String('android_admob_native_unit_id');
                $table->String('andorid_admob_interstitial_ad_unit_id');
                $table->String('ios_admob_banner_ad_unit_id');
                $table->String('ios_admob_native_ad_unit_id');
                $table->String('ios_admob_interstitial_ad_unit_id');
                $table->Integer('recent_search_keyword_limit');
                $table->String('data_config_data_source_type');
                $table->Integer('data_config_day');
                $table->tinyInteger('is_slider_auto_play')->default(0);
                $table->tinyInteger('is_demo_for_payment')->default(0);
                $table->Integer('auto_play_interval');
                $table->Integer('loading_shimmer_item_count');
                $table->Integer('phone_list_count');
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
        Schema::table('psx_mobile_settings', function (Blueprint $table) {
            $table->dropColumn([
                'android_admob_banner_ad_unit_id',
                'android_admob_native_unit_id',
                'andorid_admob_interstitial_ad_unit_id',
                'ios_admob_banner_ad_unit_id',
                'ios_admob_native_ad_unit_id',
                'ios_admob_interstitial_ad_unit_id',
                'recent_search_keyword_limit',
                'data_config_data_source_type',
                'data_config_day',
                'is_slider_auto_play',
                'is_demo_for_payment',
                'auto_play_interval',
                'loading_shimmer_item_count',
                'phone_list_count',
            ]);
        });
    }
};
