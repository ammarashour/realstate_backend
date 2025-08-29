<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Installer;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psx_installer', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("is_installed")->default(0);
        });

        $installedFile = file_exists(storage_path('installed'));
        $install = Installer::first();
        if (!empty($installedFile)){
            if (!empty($install)){
                $install->is_installed = 1;
                $install->update();
            } else {
                $installerNew = new Installer();
                $installerNew->is_installed = 1;
                $installerNew->save();
            }
        } else {
            if (!empty($install)){
                $install->is_installed = 0;
                $install->update();
            } else {
                $installerNew = new Installer();
                $installerNew->is_installed = 0;
                $installerNew->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_installer');
    }
};
