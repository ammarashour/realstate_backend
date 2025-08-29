<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Route;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Payment\Entities\PaymentInfo;

class CheckExistsForIapProductId implements InvokableRule
{

    public $paymentMethod;

    public function __construct($paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function __invoke($attribute, $value, $fail)
    {

        if ($this->paymentMethod == constants::iapPaymentMethod) {
            if (empty($value)) {
                $fail('The :attribute field is required');
            }

            $iapProductIds = [];
            $iapPayments = PaymentInfo::with(['core_key', 'statusAttribute'])->where("payment_id", Constants::promotionInAppPurchasePaymentId)->get();
            foreach ($iapPayments as $iapPayment) {
                $iapProductId = $iapPayment->core_key->name;
                array_push($iapProductIds, $iapProductId);
            }
            if (!in_array($value, $iapProductIds)) {
                $fail('The selected :attribute field is invalid');
            }
        }
    }
}
