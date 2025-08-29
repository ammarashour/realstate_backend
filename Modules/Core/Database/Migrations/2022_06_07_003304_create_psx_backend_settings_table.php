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
        //        $module->title = 'backend_settings';
        //        $module->added_date = Carbon::now();
        //        $module->added_user_id = '1';
        //        $module->save();

        Schema::create('psx_backend_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('receive_email')->nullable();
            $table->string('fcm_api_key')->nullable();
            $table->string('app_token')->nullable();
            $table->string('topics')->nullable();
            $table->string('topics_fe')->nullable();
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_user')->nullable();
            $table->string('smtp_pass')->nullable();
            $table->string('smtp_encryption')->nullable()->default('tls');
            $table->boolean('email_verification_enabled')->default(1)->nullable();
            $table->boolean('user_social_info_override')->default(1)->nullable();
            $table->integer('landscape_width')->nullable();
            $table->integer('potrait_height')->nullable();
            $table->integer('square_height')->nullable();
            $table->integer('landscape_thumb_width')->nullable();
            $table->integer('potrait_thumb_height')->nullable();
            $table->integer('square_thumb_height')->nullable();
            $table->integer('landscape_thumb2x_width')->nullable();
            $table->integer('potrait_thumb2x_height')->nullable();
            $table->integer('square_thumb2x_height')->nullable();
            $table->integer('landscape_thumb3x_width')->nullable();
            $table->integer('potrait_thumb3x_height')->nullable();
            $table->integer('square_thumb3x_height')->nullable();
            $table->integer('search_item_limit')->default(5)->nullable();
            $table->integer('search_user_limit')->default(5)->nullable();
            $table->integer('search_category_limit')->default(5)->nullable();
            $table->string('dyn_link_key')->nullable();
            $table->string('dyn_link_url')->nullable();
            $table->string('dyn_link_package_name')->nullable();
            $table->string('dyn_link_domain')->nullable();
            $table->string('dyn_link_deep_url')->nullable();
            $table->string('ios_boundle_id')->nullable();
            $table->string('ios_appstore_id')->nullable();
            $table->string('backend_version_no')->nullable();
            $table->integer('slow_moving_item_limit')->default(90)->nullable();
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
        Schema::dropIfExists('psx_backend_settings');
    }
};
