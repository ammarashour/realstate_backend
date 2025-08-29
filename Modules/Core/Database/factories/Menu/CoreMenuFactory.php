<?php

namespace Modules\Core\Database\factories\Menu;

use App\Models\User;
use Modules\Core\Entities\Menu\Module;
use Modules\Core\Entities\Menu\CoreMenu;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoreMenuFactory extends Factory
{
    protected $model = CoreMenu::class;

    public function definition()
    {
        return [
            'module_name' => $this->faker->name,
            'module_desc' => $this->faker->name,
            'module_lang_key' => $this->faker->word(),
            'icon_id' => 1,
            'ordering' => 1,
            'is_show_on_menu' => 1,
            'module_id' => Module::factory(),
            'core_sub_menu_group_id' => $this->faker->numberBetween(0, 50),
            'added_user_id' => User::factory(),
        ];
    }
}
