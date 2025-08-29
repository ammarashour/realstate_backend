<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Project;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psx_theme_screens', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->foreignId('platform_id')->constrained('psx_theme_platform');
            $table->boolean('is_publish');
            $table->timestamp('added_date');
            $table->foreignId('added_user_id');
            $table->timestamp('updated_date')->nullable();
            $table->foreignId('updated_user_id')->nullable();
            $table->smallInteger('updated_flag')->nullable();
        });

        $project = Project::first();
        $baseData = [
            'name' => 'Dashboard Screen',
            'is_publish' => 1,
            'added_date' => now(),
            'added_user_id' => 1
        ];
        // MPC
        if ($project->base_project_id == 11) {
            DB::table('psx_theme_screens')->insert([
                [
                    'id' => 'ps-sweb00001',
                    'platform_id' => 1,
                    ...$baseData
                ],
                [
                    'id' => 'ps-smb00001',
                    'platform_id' => 2,
                    ...$baseData
                ]
            ]);
        }
        //MOC
        if ($project->base_project_id == 26) {
            DB::table('psx_theme_screens')->insert([
                [
                    'id' => 'ps-sweb00003',
                    'platform_id' => 1,
                    ...$baseData
                ],
                [
                    'id' => 'ps-smb00002',
                    'platform_id' => 2,
                    ...$baseData
                ]
            ]);
        }
        //REC
        if ($project->base_project_id == 27) {
            DB::table('psx_theme_screens')->insert([
                [
                    'id' => 'ps-sweb00004',
                    'platform_id' => 1,
                    ...$baseData
                ],
                [
                    'id' => 'ps-smb00003',
                    'platform_id' => 2,
                    ...$baseData
                ]
            ]);
        }
        //CGC
        if ($project->base_project_id == 28) {
            DB::table('psx_theme_screens')->insert([
                [
                    'id' => 'ps-sweb00002',
                    'platform_id' => 1,
                    ...$baseData
                ],
                [
                    'id' => 'ps-smb00004',
                    'platform_id' => 2,
                    ...$baseData
                ]
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('psx_theme_screens');
    }
};
