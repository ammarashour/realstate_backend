<?php

namespace Modules\Core\Database\factories;

use App\Models\User;
use Modules\Core\Entities\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition()
    {
        return [
            'id' => $this->faker->numberBetween(0, 1000),
            'project_name' => $this->faker->name,
            'ps_license_code' => $this->faker->slug(),
            'project_code' => $this->faker->iban(),
            'project_url' => $this->faker->url(),
            'base_project_id' => $this->faker->numberBetween(0, 100),
            'api_key' => $this->faker->uuid(),
            'token' => $this->faker->uuid(),
            'first_time_sync' => $this->faker->numberBetween(0, 1),
            'added_user_id' => User::factory(),
            'updated_user_id' => User::factory(),
        ];
    }
}
