<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Utilities\CoreField;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $aboutMe = CoreField::where([
                'module_name' => 'usr',
                'field_name' => 'user_about_me'
            ])->first();

        if($aboutMe){
            $aboutMe->enable = 1;
            $aboutMe->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $aboutMe = CoreField::where([
                'module_name' => 'usr',
                'field_name' => 'user_about_me'
            ])->first();

        if($aboutMe){
            $aboutMe->enable = 0;
            $aboutMe->update();
        }
    }
};
