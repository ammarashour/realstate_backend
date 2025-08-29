<?php

namespace Modules\Core\Database\factories\Category;

use Modules\Core\Entities\Category\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'ordering' => 1,
            'status' => 0,
            'added_user_id' => 1,
        ];
    }
}
