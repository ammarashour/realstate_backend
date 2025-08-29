<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Configuration\CoreKeyService;
use Modules\Core\Http\Services\CoreKeyCounterService;
use Modules\Core\Http\Services\Financial\CoreKeyPaymentRelationService;
use Modules\Core\Http\Services\Financial\PaymentAttributeService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Payment\Entities\PaymentInfo;

return new class extends Migration
{

    public function up()
    {
        try{
            $coreKeyPaymentRelationService = app()->make(CoreKeyPaymentRelationService::class);
            $paymentAttributeService = app()->make(PaymentAttributeService::class);
            $coreKeyService = app()->make(CoreKeyService::class);

            $dummyDataArr = [
                (object)[
                    "id" => 1,
                    "in_app_purchase_prd_id" => "promo_10_day",
                    "title" => "Bronze",
                    "duration" => 1,
                    "sale_price" => 100,
                    "discount_price" => 0,
                    "is_most_popular_plan" => 0,
                    "status" => 1,
                    "currency_id" => 1
                ],
                (object)[
                    "id" => 2,
                    "in_app_purchase_prd_id" => "promo_7_day",
                    "title" => "Gold",
                    "duration" => 6,
                    "sale_price" => 2000,
                    "discount_price" => 0,
                    "is_most_popular_plan" => 1,
                    "status" => 1,
                    "currency_id" => 1
                ],
                (object)[
                    "id" => 3,
                    "in_app_purchase_prd_id" => "promo_1_day",
                    "title" => "Diamond",
                    "duration" => 12,
                    "sale_price" => 1000,
                    "discount_price" => 0,
                    "is_most_popular_plan" => 0,
                    "status" => 1,
                    "currency_id" => 1
                ]
            ];

            foreach($dummyDataArr as $dummyDataObj){
                // save core key table
                $coreKey = new \stdClass();
                $coreKey->name = $dummyDataObj->in_app_purchase_prd_id;
                $coreKey->description = $dummyDataObj->in_app_purchase_prd_id;
                $coreKey->added_user_id = 1;
                $core_key = $coreKeyService->save($coreKey, Constants::payment);

                // save core key payment relations table
                $coreKeyPaymentRelation = new \stdClass();
                $coreKeyPaymentRelation->core_keys_id = $core_key->core_keys_id;
                $coreKeyPaymentRelation->payment_id = "payment00008";
                $coreKeyPaymentRelation->added_user_id = 1;
                $coreKeyPaymentRelationService->save((array)$coreKeyPaymentRelation);

                // save payment info table
                $paymentInfo = new PaymentInfo();
                $paymentInfo->core_keys_id = $core_key->core_keys_id;
                $paymentInfo->payment_id = "payment00008";
                if (isset($dummyDataObj->title) && !empty($dummyDataObj->title))
                    $paymentInfo->value = $dummyDataObj->title;

                $paymentInfo->added_user_id = 1;
                $paymentInfo->save();

                // save payment attributes table For Duration Col
                $paymentAttributeType = new PaymentAttribute();
                $paymentAttributeType->payment_id = "payment00008";
                $paymentAttributeType->core_keys_id = $core_key->core_keys_id;
                $paymentAttributeType->attribute_key = 'duration';
                $paymentAttributeType->attribute_value = $dummyDataObj->duration;
                $paymentAttributeType->added_user_id = 1;
                $paymentAttributeService->save((array)$paymentAttributeType);

                // save payment attributes table For Sale Price Col
                $paymentAttributeCount = new PaymentAttribute();
                $paymentAttributeCount->payment_id = "payment00008";
                $paymentAttributeCount->core_keys_id = $core_key->core_keys_id;
                $paymentAttributeCount->attribute_key = 'sale_price';
                $paymentAttributeCount->attribute_value = $dummyDataObj->sale_price;
                $paymentAttributeCount->added_user_id = 1;
                $paymentAttributeService->save((array)$paymentAttributeCount);

                // save payment attributes table For Discount Price Col
                $paymentAttributePrice = new PaymentAttribute();
                $paymentAttributePrice->payment_id = "payment00008";
                $paymentAttributePrice->core_keys_id = $core_key->core_keys_id;
                $paymentAttributePrice->attribute_key = 'discount_price';
                $paymentAttributePrice->attribute_value = $dummyDataObj->discount_price;
                $paymentAttributePrice->added_user_id = 1;
                $paymentAttributeService->save((array)$paymentAttributePrice);

                // save payment attributes table For Is Most Popular Plan Col
                $paymentAttributeStatus = new PaymentAttribute();
                $paymentAttributeStatus->payment_id = "payment00008";
                $paymentAttributeStatus->core_keys_id = $core_key->core_keys_id;
                $paymentAttributeStatus->attribute_key = 'is_most_popular_plan';
                $paymentAttributeStatus->attribute_value = $dummyDataObj->is_most_popular_plan == false?'0':'1';
                $paymentAttributeStatus->added_user_id = 1;
                $paymentAttributeService->save((array)$paymentAttributeStatus);

                // save payment attributes table For Status Col
                $paymentAttributeStatus = new PaymentAttribute();
                $paymentAttributeStatus->payment_id = "payment00008";
                $paymentAttributeStatus->core_keys_id = $core_key->core_keys_id;
                $paymentAttributeStatus->attribute_key = 'status';
                $paymentAttributeStatus->attribute_value = $dummyDataObj->status == false?'0':'1';
                $paymentAttributeStatus->added_user_id = 1;
                $paymentAttributeService->save((array)$paymentAttributeStatus);

                // save payment attributes table For Currency Col
                $paymentAttributeCurrency = new PaymentAttribute();
                $paymentAttributeCurrency->payment_id = "payment00008";
                $paymentAttributeCurrency->core_keys_id = $core_key->core_keys_id;
                $paymentAttributeCurrency->attribute_key = 'currency_id';
                $paymentAttributeCurrency->attribute_value = $dummyDataObj->currency_id;
                $paymentAttributeCurrency->added_user_id = 1;
                $paymentAttributeService->save((array)$paymentAttributeCurrency);
            }
        }catch(Exception $_) {}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
