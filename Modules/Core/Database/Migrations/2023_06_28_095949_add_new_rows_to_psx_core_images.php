<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Configuration\FrontendSetting;
use Modules\Core\Entities\Menu\CoreMenu;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('', function (Blueprint $table) {});
        $setting = FrontendSetting::first();
        try {
            $parent = $setting->id;
            $logo = CoreImage::where('img_type', 'frontend-logo')->where('img_parent_id', $parent)->first();
            if (!$logo) {
                $logo = new CoreImage();
                $logo->img_type = "frontend-logo";
                $logo->img_parent_id = $parent;
                $logo->img_path = "649c03d088fab_.png";
                $logo->img_width = "256";
                $logo->img_height = "256";
                $logo->ordering = "1";
                $logo->added_user_id = "1";
                $logo->save();
            }

            $icon = CoreImage::where('img_type', 'frontend-icon')->where('img_parent_id', $parent)->first();
            if (!$icon) {
                $icon = new CoreImage();
                $icon->img_type = "frontend-icon";
                $icon->img_parent_id = $parent;
                $icon->img_path = "649c03d160d44_.ico";
                $icon->ordering = "1";
                $icon->added_user_id = "1";
                $icon->save();
            }

            $banner = CoreImage::where('img_type', 'frontend-banner')->where('img_parent_id', $parent)->first();
            if (!$banner) {
                $banner = new CoreImage();
                $banner->img_type = "frontend-banner";
                $banner->img_parent_id = $parent;
                $banner->img_path = "649c03d196fc3_.jpg";
                $banner->img_width = "1000";
                $banner->img_height = "2048";
                $banner->ordering = "1";
                $banner->added_user_id = "1";
                $banner->save();
            }

            $menu = CoreMenu::where('module_name', 'frontend_setting')->first();

            if ($menu && $menu->is_show_on_menu == 0) {
                $menu->is_show_on_menu = 1;
                $menu->update();
            }
        } catch (Exception $_) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {});
    }
};
