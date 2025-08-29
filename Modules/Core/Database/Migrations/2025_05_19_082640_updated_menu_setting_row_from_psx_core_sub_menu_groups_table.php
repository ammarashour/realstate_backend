<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CoreSubMenuGroup::where(CoreSubMenuGroup::subMenuName, 'menu_setting')->update([
            CoreMenuGroup::isShowOnMenu => 0
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CoreSubMenuGroup::where(CoreSubMenuGroup::subMenuName, 'menu_setting')->update([
            CoreMenuGroup::isShowOnMenu => 1
        ]);
    }
};
