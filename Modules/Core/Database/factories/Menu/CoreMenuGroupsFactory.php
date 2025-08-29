<?php

namespace Modules\Core\Database\factories\Menu;

use Modules\Core\Entities\Menu\Module;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoreMenuGroupsFactory extends Factory
{
    protected $model = CoreSubMenuGroup::class;

    public function definition()
    {
        return [
            'sub_menu_name' => $this->faker->name,
            'sub_menu_desc' => $this->faker->name,
            'icon_id' => $this->faker->numberBetween(0, 50),
            'sub_menu_lang_key' => $this->faker->name,
            'ordering' => 1,
            'is_show_on_menu' => $this->faker->numberBetween(0, 1),
            'module_id' => Module::factory(),
            'core_menu_group_id' => $this->faker->numberBetween(0, 5),
            'added_user_id' => 1,
            'is_dropdown' => $this->faker->numberBetween(0, 1)
        ];
    }
}
