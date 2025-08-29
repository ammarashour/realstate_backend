<?php

namespace Modules\Core\Database\factories\Authorization;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Authorization\RolePermission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RolePermissionFactory extends Factory
{
    protected $model = RolePermission::class;

    public function definition()
    {
        return [
            "role_id" => 1,
            "module_id" => 1,
            "permission_id" => "1,2,3,4",
            "added_user_id" => 1,
        ];
    }
}
