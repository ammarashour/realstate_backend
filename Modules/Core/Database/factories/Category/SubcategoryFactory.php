<?php

namespace Modules\Core\Database\factories\Category;

use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Category\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'category_id' => Category::factory(),
            'ordering' => 1,
            'status' => 0,
            'added_user_id' => 1,
        ];
    }
}
