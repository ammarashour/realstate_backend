<?php

namespace Modules\Payment\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Entities\PaymentAttribute;

class PaymentAttributeFactory extends Factory
{
    protected $model = PaymentAttribute::class;
    
    public function definition()
    {
        return [
            'payment_id' => Payment::factory(),
            'core_keys_id' => $this->faker->latitude(),
            'attribute_key' => $this->faker->longitude(),
            'attribute_value' => 'psx',
            'added_user_id' => 1,
        ];
    }
}