<?php

namespace Modules\Core\Database\factories\Utilities;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class CoreFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CoreField::class;

    public function definition()
    {
        return [
            "placeholder" => "loc00001_placeholder",
            "mandatory" => 0,
            "label_name" => "testing",
            "field_name" => "id",
            "is_show_sorting" => 1,
            "is_show_in_filter" => 1,
            "ordering" => 1,
            "enable" => 1,
            "is_delete" => 0,
            "module_name" => "loc",
            "data_type" => "String",
            "table_id" => 1,
            "project_id" => 1,
            "project_name" => "Testing",
            "base_module_name" => 16,
            "is_include_in_hideshow" => 1,
            "is_show" => 1,
            "is_core_field" => 1,
            "permission_for_enable_disable" => 0,
            "permission_for_delete" => 0,
            "permission_for_mandatory" => 0,
            "added_date" => "",
            "added_user_id" => 1,
            "updated_date" => "",
            "updated_user_id" => 1,
            "updated_flag" => ""
        ];
    }
}
