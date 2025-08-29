<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Route;
use Modules\Payment\Entities\PaymentInfo;

class CheckVendorDeliveryCharge implements InvokableRule
{

    public $deliverySetting;

    public function __construct($deliverySetting = null)
    {
        $this->deliverySetting = $deliverySetting;
    }

    public function __invoke($attribute, $value, $fail)
    {
        if($value < 0){
            $fail('The :attribute is invalid');
        }
    }
}
