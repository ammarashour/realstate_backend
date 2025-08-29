<?php

namespace Modules\Core\Database\factories\Authorization;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Role::class;

    public function definition()
    {
        return [
            "id" => 1,
            "name" => "Super Admin",
            "status" => 1,
            "added_user_id" => 1,
        ];
    }
}
