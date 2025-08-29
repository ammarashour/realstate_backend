<?php

namespace Modules\Core\Database\factories\Menu;

use App\Models\User;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoreMenuGroupFactory extends Factory
{
    protected $model = CoreMenuGroup::class;

    public function definition()
    {
        return [
            'group_name' => $this->faker->name,
            'group_lang_key' => $this->faker->word(),
            'group_icon' => 'setting',
            'is_show_on_menu' => '1',
            'is_invisible_group_name' => '0',
            'added_user_id' => User::factory(),
            'updated_user_id' => User::factory(),
        ];
    }
}
