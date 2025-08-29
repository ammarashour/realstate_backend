<?php

namespace Modules\Core\Database\factories;

use App\Models\User;
use Modules\Core\Entities\UserInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserInfoFactory extends Factory
{
    protected $model = UserInfo::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'core_keys_id' => 'ps-usr00001',
            'value' => 'City Name',
            'added_user_id' => 1
        ];
    }
}
