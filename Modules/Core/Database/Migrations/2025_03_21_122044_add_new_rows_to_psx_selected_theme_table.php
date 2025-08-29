<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Entities\Project;
use Modules\Theme\Entities\SelectedTheme;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            $project = Project::first();
            $baseData = [
                'theme_id' => 1,
                'project_id' => $project->id,
                'added_date' => now(),
                'added_user_id' => 0
            ];
            if ($project->base_project_id == 11) {
                DB::table(SelectedTheme::tableName)->insert([
                    'theme_name' => 'MPC Default Theme 1',
                    ...$baseData
                ]);
            }
            if ($project->base_project_id == 26) {
                DB::table(SelectedTheme::tableName)->insert([
                    'theme_name' => 'MOC Default Theme 1',
                    ...$baseData
                ]);
            }
            if ($project->base_project_id == 27) {
                DB::table(SelectedTheme::tableName)->insert([
                    'theme_name' => 'REC Default Theme 1',
                    ...$baseData
                ]);
            }
            if ($project->base_project_id == 28) {
                DB::table(SelectedTheme::tableName)->insert([
                    'theme_name' => 'CGC Default Theme 1',
                    ...$baseData
                ]);
            }
        } catch (Throwable $e) {
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
