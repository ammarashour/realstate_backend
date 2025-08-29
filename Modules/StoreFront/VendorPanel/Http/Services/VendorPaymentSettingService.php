<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Models\User;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\CoreKey;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

use Modules\Core\Entities\VendorPayment;
use Modules\Core\Entities\VendorPaymentInfo;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Payment\Transformers\Backend\NoModel\PaymentSetting\PaymentSettingWithKeyResource;
use Modules\StoreFront\VendorPanel\Transformers\Backend\NoModel\VendorPaymentSetting\VendorPaymentSettingWithKeyResource;

class VendorPaymentSettingService extends PsService
{
    const parentPath = "Pages/vendor/views/payment_settings/";
    const indexPath = self::parentPath . "Index";

    protected $vendorPaymentService, $paymentService;

    public function __construct(VendorPaymentService $vendorPaymentService, PaymentService $paymentService)
    {
        $this->vendorPaymentService = $vendorPaymentService;
        $this->paymentService = $paymentService;
    }

    public function index($request)
    {
        $vendor_id = getVendorIdFromSession();

        $vendorPayments = VendorPaymentSettingWithKeyResource::collection($this->setVendorPaymentInfo($vendor_id));

        $dataArr = [
            "vendorId" => $vendor_id,
            "vendorPayments" => $vendorPayments,
            "paypalPaymentId" => Constants::paypalPaymentId,
            "stripePaymentId" => Constants::stripePaymentId,
            "razorPaymentId" => Constants::razorPaymentId,
            "paystackPaymentId" => Constants::paystackPaymentId,
            "flutterwavePaymentId" => Constants::flutterwavePaymentId,
        ];

        return renderView(self::indexPath, $dataArr);
    }

    public function store($request, $vendorId)
    {

        $payments = $request->toArray();
        foreach ($payments as $payment) {
            $vendorPayment = VendorPayment::where([VendorPayment::vendorId => $vendorId, VendorPayment::paymentId => $payment["payment_id"]])->first();
            $vendorPayment->status = $payment["status"];
            $vendorPayment->updated_user_id = Auth::user()->id;
            $vendorPayment->update();
            foreach ($payment["payment_relation"] as $relation) {
                $vendorPaymentInfo = VendorPaymentInfo::where([VendorPaymentInfo::vendorId => $vendorId, VendorPaymentInfo::id => $relation["id"]])->first();
                $vendorPaymentInfo->value = $relation["value"];
                $vendorPaymentInfo->updated_user_id = Auth::user()->id;
                $vendorPaymentInfo->update();
            }
        }

        return redirect()->back();
    }

    public function setVendorPaymentInfo($vendorId)
    {
        $vendorPaymentInfos = VendorPaymentInfo::where(VendorPaymentInfo::vendorId, $vendorId)->first();

        if (empty($vendorPaymentInfos)) {
            $vendorPayments = $this->vendorPaymentService->getPayments($vendorId, ['payment_relation'], null, null, null, null, true, null);
            foreach ($vendorPayments as $payment) {
                foreach ($payment["payment_relation"] as $relation) {
                    $vendorPaymentInfo = new VendorPaymentInfo();
                    $vendorPaymentInfo->payment_id = $relation["payment_id"];
                    $vendorPaymentInfo->core_keys_id = $relation["core_keys_id"];
                    $vendorPaymentInfo->vendor_id = $vendorId;
                    $vendorPaymentInfo->save();
                }
            }
        }

        $removeIds = [Constants::offlinePaymentId, Constants::promotionInAppPurchasePaymentId, Constants::packageInAppPurchasePaymentId, Constants::vendorSubscriptionPlanPaymentId, 'payment00009'];
        $relations = ['payment', 'payment_relation' => function ($query) use ($vendorId) {
            $query->with(['vendor_payment_infos' => function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            }]);
        }];
        $vendorPayments = $this->vendorPaymentService->getPayments($vendorId, $relations, null, null, null, $removeIds, true);

        return $vendorPayments;
    }

    public function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), "action", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);
        return $controlFieldArr;
    }

    public function getVendorPaymentInfo($id = null, $relations = null, $core_keys_id = null, $conds = null, $vendorId = null)
    {
        $paymentInfo = VendorPaymentInfo::when($id, function ($query, $id) {
            $query->where(VendorPaymentInfo::id, $id);
        })->when($core_keys_id, function ($query, $core_keys_id) {
            $query->where(VendorPaymentInfo::coreKeysId, $core_keys_id);
        })
            ->when($vendorId, function ($query, $vendorId) {
                $query->where(VendorPaymentInfo::vendorId, $vendorId);
            })
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $paymentInfo;
    }
}
