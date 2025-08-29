<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Table;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $coreKeyTypeId = DB::table('psx_core_key_types')->insertGetId([
            'code' => 'ps-theme-screen',
            'client_code' => 'theme-screen',
            'name' => 'Theme Screen',
            'description' => 'Theme Screen Desc',
            'added_date' => now(),
            'added_user_id' => '0'
        ]);

        $tableId = Table::orderByDesc('id')->first()->id + 1;
        DB::table('psx_tables')->insert([
            'id' => $tableId,
            'name' => 'PSX Theme Screen',
            'description' => 'Theme Screen Table',
            'core_key_type_id' => $coreKeyTypeId,
            'is_only_for_core_field' => 1,
            'table_used_type_id' => 1,
            'added_date' => now(),
            'added_user_id' => now()
        ]);

        $project = Project::first();
        $baseData = [
            'module_name' => 'theme-screen',
            'table_id' => $tableId,
            'base_module_name' => 'ps-theme-screen',
            'project_id' => $project->id,
            'project_name' => $project->project_name,
            'is_core_field' => 1,
            'is_delete' => 0,
            'is_show_sorting' => 0,
            'is_show_in_filter' => 0,
            'is_include_in_hideshow' => 0,
            'permission_for_enable_disable' => 0,
            'permission_for_delete' => 0,
            'added_date' => now(),
            'added_user_id' => 1
        ];
        $coreFieldFitlerTable = 'psx_core_field_filter_settings';

        $screenNameFieldId = DB::table($coreFieldFitlerTable)->insertGetId([
            ...$baseData,
            'field_name' => 'name',
            'data_type' => 'String',
            'is_show' => 1,
            'mandatory' => 1,
            'ordering' => 1
        ]);
        DB::table($coreFieldFitlerTable)->where('id', $screenNameFieldId)->update($this->generateLabelAndPlaceholder($screenNameFieldId));

        $platformIdFieldId = DB::table($coreFieldFitlerTable)->insertGetId([
            ...$baseData,
            'field_name' => 'platform_id',
            'data_type' => 'Integer',
            'is_show' => 1,
            'mandatory' => 1,
            'ordering' => 2
        ]);
        DB::table($coreFieldFitlerTable)->where('id', $platformIdFieldId)->update($this->generateLabelAndPlaceholder($platformIdFieldId));

        $isPublishFieldId = DB::table($coreFieldFitlerTable)->insertGetId([
            ...$baseData,
            'field_name' => 'is_publish',
            'data_type' => 'Boolean',
            'is_show' => 1,
            'mandatory' => 1,
            'ordering' => 3
        ]);
        DB::table($coreFieldFitlerTable)->where('id', $isPublishFieldId)->update($this->generateLabelAndPlaceholder($isPublishFieldId));

        $baseData = [
            'module_name' => Constants::themeScreen,
            'added_date' => now(),
            'added_user_id' => 1,
        ];
        DB::table('psx_screen_display_ui_settings')->insert([
            [
                'key' => 'name',
                'is_show' => 1,
                ...$baseData
            ],
            [
                'key' => 'platform_id',
                'is_show' => 1,
                ...$baseData
            ],
            [
                'key' => 'is_publish',
                'is_show' => 1,
                ...$baseData
            ]
        ]);
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

    private function generateLabelAndPlaceholder($id)
    {
        return [
            'label_name' => "core_key_{$id}_name",
            'placeholder' => "core_key_{$id}_placeholder"
        ];
    }
};
