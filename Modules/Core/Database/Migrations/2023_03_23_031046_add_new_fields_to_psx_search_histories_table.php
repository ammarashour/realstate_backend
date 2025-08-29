<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\SearchHistory;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('psx_search_histories', function (Blueprint $table) {
            $table->after("type", function ($table){
                $table->tinyInteger('is_home_page_search')->default(0);
            });

        });

        $searchHistory = SearchHistory::where('is_home_page_search', 0)->update(['is_home_page_search' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('psx_search_histories', function (Blueprint $table) {
            $table->dropColumn(['is_home_page_search']);
        });
    }
};
