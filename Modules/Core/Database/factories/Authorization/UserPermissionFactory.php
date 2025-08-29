<?php

namespace Modules\Core\Database\factories\Authorization;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Authorization\UserPermission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserPermissionFactory extends Factory
{
    protected $model = UserPermission::class;

    public function definition()
    {
        return [
            "user_id" => 1,
            "role_id" => 1,
            "added_user_id" => 1,
        ];
    }
}
