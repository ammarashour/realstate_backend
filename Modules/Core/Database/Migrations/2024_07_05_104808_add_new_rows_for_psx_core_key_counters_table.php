<?php

use Carbon\Carbon;
use Modules\Core\Entities\Category\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Core\Entities\CoreKeyCounter;
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
        $categoryCount = Category::get()->count();
        $coreKeyCounter = new CoreKeyCounter();
        $coreKeyCounter->code = 'ctg-lang';
        $coreKeyCounter->counter = $categoryCount;
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
