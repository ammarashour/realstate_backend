<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\CoreKeyCounter;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $coreKeyCounter = new CoreKeyCounter();
        $coreKeyCounter->code = 'sub-ctg-lang';
        $coreKeyCounter->counter = 1;
        $coreKeyCounter->added_date = Carbon::now();
        $coreKeyCounter->added_user_id = '1';
        $coreKeyCounter->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
