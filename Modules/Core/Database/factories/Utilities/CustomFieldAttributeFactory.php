<?php

namespace Modules\Core\Database\factories\Utilities;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class CustomFieldAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CustomFieldAttribute::class;

    public function definition()
    {
        return [
            "core_keys_id" => "loc00001",
            "name" => "test",
            "added_date" => "",
            "added_user_id" => 1,
            "updated_date" => "",
            "updated_user_id" => "",
            "updated_flag" => ""
        ];
    }
}
