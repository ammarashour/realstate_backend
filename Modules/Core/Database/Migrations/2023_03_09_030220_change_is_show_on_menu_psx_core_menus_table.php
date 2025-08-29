<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Menu\CoreMenu;
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
        //
        CoreMenu::where("id", "55")->delete();
        CoreMenu::where("id", "54")->delete();
        CoreMenu::where("id", "9")->delete();
        CoreMenu::where("id", "10")->delete();
        CoreMenu::where("id", "11")->delete();
        CoreMenu::where("id", "12")->delete();
        CoreMenu::where("id", "21")->delete();
        CoreMenu::where("id", "37")->delete();
        CoreMenu::where("id", "33")->delete();
        CoreMenu::where("id", "33")->delete();

        CoreSubMenuGroup::where("id", "10")->delete();

        CoreMenu::where("id", "60")->update(["is_show_on_menu" => '0']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
