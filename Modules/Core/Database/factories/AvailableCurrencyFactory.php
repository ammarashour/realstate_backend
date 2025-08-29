<?php

namespace Modules\Core\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

class AvailableCurrencyFactory extends Factory
{
    protected $model = AvailableCurrency::class;

    public function definition()
    {
        return [
            'currency_symbol' => $this->faker->sentence,
            'currency_short_form' => $this->faker->sentence,
            'name' => $this->faker->sentence,
            'status' => '0',
            'is_default' => '1',
            'added_user_id' => 1,
        ];
    }
}
