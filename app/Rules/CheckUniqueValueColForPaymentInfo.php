<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Route;
use Modules\Payment\Entities\PaymentInfo;

class CheckUniqueValueColForPaymentInfo implements InvokableRule
{

    public $id, $paymentId;

    public function __construct($id = null, $paymentId = null)
    {
        $this->id = $id;
        $this->paymentId = $paymentId;
    }

    public function __invoke($attribute, $value, $fail)
    {
        if(empty($this->id)){
            $paymentInfosValueCol = PaymentInfo::where("payment_id", $this->paymentId)->get()->pluck("value")->toArray();
            if(in_array($value, $paymentInfosValueCol)){
                $fail('The :attribute has already been taken');
            }
        } else {
            $paymentInfo = PaymentInfo::where("id" ,"!=" ,$this->id)->where("payment_id", $this->paymentId)->first();
            if($paymentInfo->value == $value){
                $fail('The :attribute has already been taken');
            }
        }

    }
}
