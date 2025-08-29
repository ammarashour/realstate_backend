<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Vendor;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\SubscriptionBoughtTransaction;
use App\Http\Contracts\Vendor\VendorSubscriptionPlanBoughtTransactionServiceInterface;
use Modules\Core\Transformers\Backend\NoModel\VendorReport\VendorSubscriptionTransactionWithKeyResource;
use Modules\Payment\Http\Services\PaymentSettingService;

class VendorSubscriptionReportController extends PsController
{
    private const parentPath = "vendor_subscription_report/";
    private const indexPath = self::parentPath . "Index";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "vendor_subscription_report.index";
    private const editRoute = "vendor_subscription_report.edit";

    public function __construct(protected VendorSubscriptionPlanBoughtTransactionServiceInterface $vendorSubscriptionPlanBoughtTransactionService,
    protected PaymentSettingService $paymentSettingService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::vendorSubscriptionReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::vendorSubscriptionReportModule, ps_constant::readPermission, Auth::id());

        $dataArr['transaction'] = $this->prepareShowData($id);
        return renderView(self::editPath, $dataArr);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData($request)
    {
        $conds = [
            'searchterm' => $request->input('search'),
            'package_id' => $request->input('package_filter') == 'all' ? null  : $request->package_filter,
            'selected_date' => $request->input('date_filter') == 'all' ? null  : $request->date_filter,
            'selected_payment_method' => $request->input('selected_payment_method') == 'all' ? null  : $request->selected_payment_method,
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;
        $packageConds['payment_id'] = Constants::vendorSubscriptionPlanPaymentId;
        $packages = $this->paymentSettingService->getPaymentInfos(null, null, null, $packageConds, 1);
        
        $relation = ['user', 'package'];
        $transactions = VendorSubscriptionTransactionWithKeyResource::collection($this->vendorSubscriptionPlanBoughtTransactionService->getAll($relation, null, null, null, null, $row, $conds));

        $payment_methods_filters = [];
        $payment_methods = SubscriptionBoughtTransaction::groupBy('payment_method')->get();
        $payment_methods = SubscriptionBoughtTransaction::groupBy('payment_method')->pluck('payment_method');

        return [
            "transactions" => $transactions,
            'sort_field' => $conds['order_by'],
            'sort_order' => $request->sort_order,
            'search' => $conds['searchterm'],
            'packages' => $packages,
            'selected_package' => $conds['package_id'],
            'selected_payment_method' => $conds['selected_payment_method'],
            'payment_methods' => $payment_methods,
            'selectedDate' => $conds['selected_date'],
        ];
    }

    private function prepareShowData($id)
    {
        $relations = ['package', 'user'];

        $dataArr = new VendorSubscriptionTransactionWithKeyResource($this->vendorSubscriptionPlanBoughtTransactionService->get($id, null, $relations));
        return $dataArr;
    }
}
