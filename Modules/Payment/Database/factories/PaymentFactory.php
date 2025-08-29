<?php

namespace Modules\Payment\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Payment\Entities\Payment;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;
    
    public function definition()
    {
        return [
            'id' => 1,
            'name' => $this->faker->word(),
            'description'=>$this->faker->sentence(),
            'status' => 0,
            'added_user_id' => 1,
        ];
    }
}