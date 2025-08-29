<?php

namespace Modules\Core\Database\factories\Utilities;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class DynamicColumnVisibilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DynamicColumnVisibility::class;

    public function definition()
    {
        return [
            'module_name' => 'loc',
            'key' => 'test',
            'is_show' => 1,
            'added_date' => '',
            'added_user_id' => 1,
            'updated_date' => '',
            'updated_user_id' => '',
            'updated_flag' => '',
        ];
    }
}
