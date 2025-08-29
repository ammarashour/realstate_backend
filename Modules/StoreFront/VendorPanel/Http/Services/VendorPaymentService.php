<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use Carbon\Carbon;
use Stripe\Payout;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\CoreKeyServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\Core\Entities\Configuration\CoreKey;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\Payment;
use Modules\Core\Entities\VendorPayment;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Core\Entities\VendorPaymentInfo;
use Modules\Core\Http\Services\Financial\CoreKeyPaymentRelationService;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPayment\VendorPaymentWithKeyResource;

class VendorPaymentService extends PsService
{
    const parentPath = "Pages/vendor/views/payment_lists/";

    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "vendor_payment.index";
    const createRoute = "vendor_payment.create";
    const editRoute = "vendor_payment.edit";

    protected $paymentService, $coreKeyPaymentRelationService;

    public function __construct(protected CoreKeyServiceInterface $coreKeyService, PaymentService $paymentService, CoreKeyPaymentRelationService $coreKeyPaymentRelationService)
    {
        $this->paymentService = $paymentService;
        $this->coreKeyPaymentRelationService = $coreKeyPaymentRelationService;
    }

    public function index($request)
    {
        // check permission start
        $vendor_id = getVendorIdFromSession();
        $request->vendor_id = $vendor_id;

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $relations = ['payment'];
        $this->setVendorPayments($request);
        $payments = VendorPaymentWithKeyResource::collection($this->getPayments($vendor_id, $relations, null, null, $conds, null, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::payment, $this->controlFieldArr());

        if ($conds['order_by']) {
            $dataArr = [
                'showPaymentCols' => $columnAndColumnFilter[ps_constant::showCoreField],
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
                'payments' => $payments,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
            ];
        } else {
            $dataArr = [
                'showPaymentCols' => $columnAndColumnFilter[ps_constant::showCoreField],
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
                'payments' => $payments,
                'search' => $conds['searchterm'],
            ];
        }

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        $relations = ['payment'];
        $payment = $this->getVendorPayment($id, $relations);
        $paymentCoreKeys = $this->coreKeyService->getAll(paymentId: $payment->payment_id); // get data payment core key (custom field)

        // check permission start
        $vendor_id = getVendorIdFromSession();

        $dataArr = [
            "vendorPayment" => $payment,
            "paymentCoreKeys" => $paymentCoreKeys,
            "offlinePaymentId" => Constants::offlinePaymentId,
            "promotionIAPPaymentId" => Constants::promotionInAppPurchasePaymentId,
            "packageIAPPaymentId" => Constants::packageInAppPurchasePaymentId,
            "vendorSubscriptoinPlanPaymentId" => Constants::vendorSubscriptionPlanPaymentId
        ];
        return renderView(self::editPath, $dataArr);
    }

    public function getVendorPayment($id, $relations = null, $conds = null)
    {
        $payment = VendorPayment::where(VendorPayment::id, $id)
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->first();
        return $payment;
    }

    public function getPayments($vendorId, $relations = null, $limit = null, $offset = null, $conds = null, $removeIds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }
        $payments = VendorPayment::where(VendorPayment::vendorId, $vendorId)
            ->select(VendorPayment::tableName . '.*')
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == 'payment_id@@name') {
                    $q->join(Payment::tableName, Payment::tableName . '.' . Payment::id, '=', VendorPayment::tableName . '.' . VendorPayment::paymentId);
                    $q->select(Payment::tableName . '.' . Payment::name . " as payment_name", VendorPayment::tableName . ".*");
                }
            })
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($removeIds, function ($query, $removeIds) {
                $query->whereNotIn(VendorPayment::paymentId, $removeIds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy(VendorPayment::status, 'desc');
            });
        if ($pagPerPage) {
            $payments = $payments->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $payments = $payments->get();
        }

        return $payments;
    }

    public function setVendorPayments($request)
    {
        $vendorId = $request->vendor_id;
        $conds['exceptPaymentIds'] = [Constants::offlinePaymentId, Constants::promotionInAppPurchasePaymentId, Constants::packageInAppPurchasePaymentId, Constants::vendorSubscriptionPlanPaymentId];

        $payments = Payment::whereNotIn('id', $conds['exceptPaymentIds'])->get();

        $vendorPayments = VendorPayment::where(VendorPayment::vendorId, $vendorId)->first();

        if (empty($vendorPayments)) {
            foreach ($payments as $payment) {
                $vendorPayment = new VendorPayment();
                $vendorPayment->payment_id = $payment->id;
                $vendorPayment->vendor_id = $vendorId;
                $vendorPayment->status = $payment->status;
                $vendorPayment->added_user_id = Auth::user()->id;
                $vendorPayment->save();
            }
            $vendorPayments =  VendorPayment::where(VendorPayment::vendorId, $vendorId)->get();
        }

        return $vendorPayments;
    }

    public function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->join(Payment::tableName, Payment::tableName . '.' . Payment::id, VendorPayment::tableName . '.' . VendorPayment::paymentId);
            $query->where(function ($query) use ($search) {
                $query->where(Payment::tableName . '.' . Payment::name, 'like', '%' . $search . '%');
                $query->orWhere(Payment::tableName . '.' . Payment::description, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['status']) && $conds['status']) {
            $query->where(VendorPayment::status, $conds['status']);
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas('added_date', function ($q) use ($date_filter) {
                $q->where(VendorPayment::CREATED_AT, $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(VendorPayment::addedUserId, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy(VendorPayment::tableName . '.' . VendorPayment::id, $conds['order_type']);
            } elseif ($conds['order_by'] == 'payment_id@@name') {
                $query->orderBy('payment_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(VendorPayment::tableName . '.' . VendorPayment::CREATED_AT, 'desc');
        }

        return $query;
    }

    public function makePublishOrUnpublish($id)
    {
        $payment = $this->getVendorPayment($id);

        if ($payment->status == Constants::publish) {
            $payment->status = Constants::unPublish;
        } else {
            $payment->status = Constants::publish;
        }

        $payment->updated_user_id = Auth::user()->id;
        $payment->update();

        return redirect()->back();
    }

    public function update($id, $request)
    {

        $vendorPayment = VendorPayment::where(VendorPayment::id, $id)->first();
        $vendorPayment->status = $request->status;
        $vendorPayment->update();

        return redirect()->route(self::indexRoute);
    }

    public function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), "action", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);
        return $controlFieldArr;
    }
}
