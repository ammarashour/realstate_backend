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
        Schema::create('psx_video_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file_name')->unique();
            $table->unsignedBigInteger('file_size'); // Total file size in bytes
            $table->integer('total_chunks'); // Total number of chunks            
            $table->string('status')->default('pending');  
            $table->timestamp('added_date')->useCurrent();
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();          
        });

        Schema::create('psx_video_chunks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file_name');
            $table->integer('offset'); // The chunk index (1,2,3,...)
            $table->timestamp('added_date')->useCurrent();
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
            $table->unique(['file_name', 'offset']); // Prevents duplicate chunks
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_chunks');
        Schema::dropIfExists('video_uploads');
    }
};
