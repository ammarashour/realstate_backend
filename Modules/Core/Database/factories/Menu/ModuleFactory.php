<?php

namespace Modules\Core\Database\factories\Menu;

use App\Models\User;
use Modules\Core\Entities\Menu\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ModuleFactory extends Factory
{
    protected $model = Module::class;

    public function definition()
    {
        return [
            "id" => $this->faker->numberBetween(0, 1000),
            "title" => "Category Report",
            "lang_key" => "Category Report",
            "added_user_id" => User::factory(),
        ];
    }
}
