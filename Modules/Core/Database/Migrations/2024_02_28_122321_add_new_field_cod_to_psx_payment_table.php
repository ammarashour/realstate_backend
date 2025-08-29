<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
       
        DB::table('psx_payments')->insert([
            'id' => 'payment00009',
            'name' => 'Cash on Delivery',
            'description' => 'COD',
            'status' => 1, 
            'added_date' => now(),
            'added_user_id' => 1,
            'updated_date' => now(),
            'updated_user_id' => 1, 
            'updated_flag' => 1, 
        ]);
    }
                
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_payments', function (Blueprint $table) {
            //
        });
    }
};
