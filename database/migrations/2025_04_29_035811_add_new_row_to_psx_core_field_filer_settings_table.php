<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $coreField = CoreField::where('module_name', Constants::language)->first()->toArray();

        $isPublishCoreFieldId = CoreField::insertGetId([
            'module_name' => Constants::language,
            'field_name' => 'is_publish',
            'data_type' => 'Boolean',
            'table_id' =>  $coreField['table_id'],
            'base_module_name' => $coreField['base_module_name'],
            'project_id' => $coreField['project_id'],
            'project_name' => $coreField['project_name'],
            'is_core_field' => 1,
            'is_delete' => 0,
            'enable' => 1,
            'mandatory' => 0,
            'is_show_sorting' => 1,
            'ordering' => 4,
            'is_include_in_hideshow' => 1,
            'is_show' => 1,
            'permission_for_enable_disable' => 0,
            'permission_for_delete' => 0,
            'permission_for_mandatory' => 0,
            'added_user_id' => 1
        ]);

        CoreField::find($isPublishCoreFieldId)->update([
            'label_name' => "core_key_{$isPublishCoreFieldId}_name",
            'placeholder' => "core_key_{$isPublishCoreFieldId}_placeholder",
        ]);

        DynamicColumnVisibility::insert([
            'module_name' => Constants::language,
            'key' => 'is_publish',
            'is_show' => 1,
            'added_user_id' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
