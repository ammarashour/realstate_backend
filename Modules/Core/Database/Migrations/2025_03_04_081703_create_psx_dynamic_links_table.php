<?php

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
        Schema::create('psx_dynamic_links', function (Blueprint $table) {
            $table->string('short_code', 190)->collation('utf8mb4_bin')->primary();
            $table->json('parameters');
            $table->string('type');
            $table->timestamp('added_date')->useCurrent();
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('psx_dynamic_links');
    }
};
