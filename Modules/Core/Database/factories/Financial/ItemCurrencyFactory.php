<?php

namespace Modules\Core\Database\factories\Financial;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Entities\Financial\ItemCurrency;

class ItemCurrencyFactory extends Factory
{
    protected $model = ItemCurrency::class;

    public function definition()
    {
        return [
            'currency_short_form' => $this->faker->currencyCode,
            'currency_symbol' => $this->faker->currencyCode,
            'status' => 1,
            'is_default' => 0,
            'added_user_id' => 1,
        ];
    }
}