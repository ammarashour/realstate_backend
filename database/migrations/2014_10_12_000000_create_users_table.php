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
        $module = new Module();
        $module->title = 'users';
        $module->added_date = Carbon::now();
        $module->added_user_id = '1';
        $module->save();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('user_is_sys_admin')->default(0);
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('phone_id')->nullable();
            $table->string('apple_id')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('user_address')->nullable();
            $table->string('user_about_me')->nullable();
            $table->string('user_cover_photo')->nullable();
            $table->string('role_id')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_banned')->default(0);
            $table->string('code')->nullable();
            $table->string('overall_rating')->nullable();
            $table->boolean('is_show_email')->default(1)->nullable();
            $table->boolean('is_show_phone')->default(1)->nullable();
            $table->boolean('is_shop_admin')->default(0);
            $table->boolean('is_city_admin')->default(0);
            $table->float('user_lat', 10, 6)->nullable();
            $table->float('user_lng', 10, 6)->nullable();
            $table->smallInteger('verify_types')->nullable()->default(1);
            $table->string('added_date_timestamp')->nullable();
            $table->timestamp('added_date');
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
