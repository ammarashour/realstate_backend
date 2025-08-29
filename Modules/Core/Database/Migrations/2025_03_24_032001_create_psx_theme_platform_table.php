<?php

use App\Config\ps_constant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psx_theme_platform', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('added_date');
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
        });

        DB::table('psx_theme_platform')->insert([
            [
                'name' => ps_constant::WEB,
                'added_date' => now(),
                'added_user_id' => 1
            ],
            [
                'name' => ps_constant::MOBILE,
                'added_date' => now(),
                'added_user_id' => 1
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_theme_platform');
    }
};
