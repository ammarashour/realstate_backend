<?php
namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\StoreFront\VendorPanel\Entities\Order;
use Modules\StoreFront\VendorPanel\Entities\VendorTransaction;
use Modules\StoreFront\VendorPanel\Entities\OrderStatus;
use Modules\StoreFront\VendorPanel\Entities\ShippingAndBilling;
use Modules\Core\Exports\TransactionsExport;

use Modules\StoreFront\VendorPanel\Transformers\Backend\NoModel\Order\OrderTransactionReportWithKeyResource;
use stdClass;

class OrderTransactionReportService extends PsService
{

    const parentPath = "order_transaction_report/";
    const indexPath = self::parentPath . "Index";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "order_transaction_report.index";
    const editRoute = "order_transaction_report.edit";
    const promotePath = self::parentPath . 'Promote';

    protected $userAccessApiTokenService, $orderTransactionId, $viewAnyAbility, $orderStatusId, $added_user_id;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->csvFileName = 'order_transaction_report';

        $this->orderTransactionId = VendorTransaction::id;
        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->orderStatusId = OrderStatus::id;
        $this->added_user_id = 'added_user_id';
    }

    // public function searching($query, $conds){

    //     $query->join($this->itemTableName, $this->itemTableName.'.'.$this->itemIdCol, '=', $this->paidItmTableName.'.'.$this->paidItmItemIdCol);

    //     // search term
    //     if (isset($conds['searchterm']) && $conds['searchterm']) {
    //         $search = $conds['searchterm'];

    //         $query->where(function ($query) use ($search) {
    //             $query->where($this->itemTableName.'.'.$this->itemTitleCol, 'like', '%' . $search . '%')
    //                     ->orWhere($this->paidItmTableName.'.'.$this->paidItmAmountCol, 'like', '%' . $search . '%');

    //         });
    //     }

    //     if(isset($conds['category_id']) && $conds['category_id']){
    //         $category_filter=$conds['category_id'];
    //         $query->where($this->itemTableName.'.'.$this->itemCategoryIdCol, $category_filter);
    //     }

    //     if(isset($conds['subcategory_id']) && $conds['subcategory_id']){
    //         $sub_cat_filter = $conds['subcategory_id'];
    //         // $query->whereHas('subcategory', function($q) use($sub_cat_filter){
    //             $query->where($this->itemTableName.'.'.$this->itemSubCategoryIdCol, $sub_cat_filter);
    //         // });
    //     }

    //     if (isset($conds['item_id']) && $conds['item_id']) {
    //         $query->where($this->paidItmTableName.'.'.$this->paidItmItemIdCol, $conds['item_id']);
    //     }

    //     if (isset($conds['start_date']) && $conds['start_date']) {
    //         $query->where($this->paidItmTableName.'.'.$this->paidItmStartDateCol, $conds['start_date']);
    //     }

    //     if (isset($conds['end_date']) && $conds['end_date']) {
    //         $query->where($this->paidItmTableName.'.'.$this->paidItmEndDateCol, $conds['end_date']);
    //     }

    //     if (isset($conds['payment_method']) && $conds['payment_method']) {
    //         $query->where($this->paidItmTableName.'.'.$this->paidItmPaymentMethodCol, $conds['payment_method']);
    //     }

    //     if (isset($conds['amount']) && $conds['amount']) {
    //         $query->where($this->paidItmTableName.'.'.$this->paidItmAmountCol, $conds['amount']);
    //     }

    //     if (isset($conds['promoted_days']) && $conds['promoted_days']) {
    //         $query->where($this->paidItmTableName.'.'.$this->paidItmPromotedDaysCol, $conds['promoted_days']);
    //     }

    //     if (isset($conds['status']) && $conds['status']) {
    //         $query->where($this->paidItmTableName.'.' . $this->paidItmStatusCol, $conds['status']);
    //     }

    //     if (isset($conds['status_filter']) && $conds['status_filter']) {
    //         $today = Carbon::now();
    //         if($conds['status_filter'] == 1){
    //             $query->where($this->paidItmStartTimeStamp, '<=' ,strtotime($today))->where($this->paidItmEndTimeStamp, '>=' ,strtotime($today));
    //         }
    //         if($conds['status_filter'] == 2){
    //             $query->where($this->paidItmStartTimeStamp, '<' ,strtotime($today))->where($this->paidItmEndTimeStamp, '<' ,strtotime($today));
    //         }
    //         if($conds['status_filter'] == 3){
    //             $query->where($this->paidItmStartTimeStamp, '>' ,strtotime($today))->where($this->paidItmEndTimeStamp, '>' ,strtotime($today));
    //         }
    //     }

    //     if(isset($conds['selected_date']) && $conds['selected_date']){
    //         $date_filter=$conds['selected_date'];
    //         $new_date=date('Y-m-d', strtotime($date_filter));
    //         $query->where($this->paidItmStartTimeStamp, '<=' ,strtotime($new_date))->where($this->paidItmEndTimeStamp, '>=' ,strtotime($new_date));
    //     }

    //     if(isset($conds['added_date']) && $conds['added_date']){
    //         $date_filter = $conds['added_date'];
    //         $query->whereHas('added_date', function($q) use($date_filter){
    //             $q->where($this->paidItmAddedDateCol, $date_filter);
    //         });
    //     }

    //     if(isset($conds['added_date_range']) && $conds['added_date_range']){
    //         $added_date_filter = $conds['added_date_range'];

    //         if($added_date_filter[1] == ''){
    //             $added_date_filter[1] = Carbon::now();
    //         }
    //         $query->whereBetween($this->paidItmTableName.'.'.$this->paidItmAddedDateCol, $added_date_filter);
    //     }

    //     if (isset($conds['added_user_id']) && $conds['added_user_id']) {
    //         $query->where($this->paidItmTableName.'.'.$this->paidItmAddedUserIdCol, $conds['added_user_id']);
    //     }

    //     // order by
    //     if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']){
    //         if($conds['order_by'] == 'id'){
    //             $query->orderBy('id', $conds['order_type']);
    //         }elseif($conds['order_by'] == 'item_id@@title'){
    //             $query->orderBy('itm_title', $conds['order_type']);
    //         }elseif($conds['order_by'] == 'category'){
    //             $query->orderBy('cat_name', $conds['order_type']);
    //         }elseif($conds['order_by'] == 'subcategory'){
    //             $query->orderBy('sub_cat_name', $conds['order_type']);
    //         }else{
    //             $query->orderBy($this->paidItmTableName.'.'.$conds['order_by'], $conds['order_type']);
    //         }
    //     }

    //     return $query;
    // }

    public function getOrderTransactionReport($relation = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if(isset($conds['order_by'])){
            $sort = $conds['order_by'];
        }

        $getOrderReports = VendorTransaction::when($relation, function ($q, $relation) {
                                $q->with($relation);
                            })
                            ->when(isset($conds['order_by']) && $conds['order_by'] , function ($q) use($sort){

                                // if($sort == 'item_id@@title')
                                // {
                                //     $q->join($this->itemTableName.' as titleItems', 'titleItems.'.$this->itemIdCol, '=', $this->paidItmTableName.'.'.$this->paidItmItemIdCol);
                                //     $q->select('titleItems.'.$this->itemTitleCol.' as itm_title', $this->paidItmTableName.'.*');
                                // }else if($sort == 'category') {
                                //     $q->join($this->itemTableName.' as catItems', 'catItems.'.$this->catIdCol, '=', $this->paidItmTableName.'.'.$this->paidItmItemIdCol);
                                //     $q->join($this->catTableName, $this->catTableName.'.'.$this->catIdCol, '=', 'catItems.category_id');
                                //     $q->select($this->catTableName.'.'.$this->catNameCol.' as cat_name', $this->paidItmTableName.'.*');
                                // }else if($sort == 'subcategory')
                                // {
                                //     $q->join($this->itemTableName.' as subcatItems','subcatItems.id','=', $this->paidItmTableName.'.'.$this->paidItmItemIdCol);
                                //     $q->join($this->subCatTableName, $this->subCatTableName.'.'.$this->subCatIdCol, '=', 'subcatItems.subcategory_id');
                                //     $q->select($this->subCatTableName.'.'.$this->subCatNameCol.' as sub_cat_name', $this->paidItmTableName.'.*');
                                // }else{
                                //     $q->select($this->paidItmTableName.'.*');
                                // }

                            })
                            ->when(!isset($conds['order_by']), function ($query){
                                $query->select(VendorTransaction::tableName.'.*');
                            })
                            ->when($limit, function ($query, $limit) {
                                $query->limit($limit);
                            })
                            ->when($offset, function ($query, $offset) {
                                $query->offset($offset);
                            })
                            ->when($conds, function ($query, $conds) {
                                // $query = $this->searching($query, $conds);
                            })
                            ->when(empty($sort), function ($query, $conds){
                                $query->orderBy(VendorTransaction::tableName.'.'.VendorTransaction::addedDate, 'desc');
                            });
            if ($pagPerPage){
                $getOrderReports = $getOrderReports->paginate($pagPerPage)->onEachSide(1)->withQueryString();
            } else{
                $getOrderReports = $getOrderReports->get();
            }
        return $getOrderReports;
    }

    public function index($request)
    {
        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['status_filter'] = $request->input('status_filter') == 'all'? null  : $request->status_filter;
        $conds['payment_method'] = $request->input('payment_method') == 'all'? null  : $request->payment_method;
        $conds['selected_date'] = $request->input('date_filter') == 'all'? null  : $request->date_filter;

        $conds['order_by']= null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if($request->sort_field)
        {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        // if($request->payment_method_filter == 1)
        // {
        //     $conds['payment_method'] = $this->paypalPaymentMethod;
        // }
        // if($request->payment_method_filter == 2)
        // {
        //     $conds['payment_method'] = $this->stripePaymentMethod;
        // }
        // if($request->payment_method_filter == 3)
        // {
        //     $conds['payment_method'] = $this->razorPaymentMethod;
        // }
        // if($request->payment_method_filter == 4)
        // {
        //     $conds['payment_method'] = $this->paystackPaymentMethod;
        // }
        // if($request->payment_method_filter == 5)
        // {
        //     $conds['payment_method'] = $this->offlinePaymentMethod;
        // }
        // if($request->payment_method_filter == 6)
        // {
        //     $conds['payment_method'] = 'in app purchase';
        // }

        $relations = [
            'order',
            'owner',
            'currency',
            'vendorPaymentStatus'
        ];

        // return $this->getOrderTransactionReport($relations, null, null, $conds, false, $row);

        $orderTransactionReports = OrderTransactionReportWithKeyResource::collection($this->getOrderTransactionReport($relations, null, null, $conds, false, $row));
        // return $orderTransactionReports;

        // taking for column and columnFilterOption
        // $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        // $showProductCols = $columnAndColumnFilter['showCoreField'];
        // $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        // $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing item arr object with new format
        $changedProductObj = $orderTransactionReports;
        // $categories = $this->categoryService->getCategories(null, $this->publish);
        // $subcategories = $this->subcategoryService->getSubCategories(null, $this->publish);

        $payment_methods = [];

        $paypal = new \stdClass();
        $paypal->id = 1;
        $paypal->name = Constants::paypalPaymentMethod;
        array_push($payment_methods, $paypal);

        $stripe = new \stdClass();
        $stripe->id = 2;
        $stripe->name = Constants::stripePaymentMethod;
        array_push($payment_methods, $stripe);

        $razor = new \stdClass();
        $razor->id = 3;
        $razor->name = Constants::razorPaymentMethod;
        array_push($payment_methods, $razor);

        $paystack = new \stdClass();
        $paystack->id = 4;
        $paystack->name = Constants::paystackPaymentMethod;
        array_push($payment_methods, $paystack);

        $offline = new \stdClass();
        $offline->id = 5;
        $offline->name = Constants::offlinePaymentMethod;
        array_push($payment_methods, $offline);

        $purchase = new \stdClass();
        $purchase->id = 6;
        $purchase->name = Constants::iapPaymentMethod;
        array_push($payment_methods, $purchase);

        $cod = new \stdClass();
        $cod->id = 9;
        $cod->name = Constants::codPaymentMethod;
        array_push($payment_methods, $cod);

        $statusList = [];

        $completed = new \stdClass();
        $completed->id = 2;
        $completed->name = __('core__be_compleated_status');
        array_push($statusList, $completed);

        $ongoing = new \stdClass();
        $ongoing->id = 1;
        $ongoing->name = __('core__be_ongoing_status');
        array_push($statusList, $ongoing);

        $notYetStart = new \stdClass();
        $notYetStart->id = 3;
        $notYetStart->name = __('core__be_not_yet_start_status');
        array_push($statusList, $notYetStart);

        if($conds['order_by'])
        {
            $dataArr = [
                // 'showCoreAndCustomFieldArr' => $columnProps,
                // 'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                // 'categories' => $categories,
                // 'subcategories' => $subcategories,
                'orderTransactionReports' => $changedProductObj,
                'statusList' => $statusList,
                'payment_methods' => $payment_methods,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
                'selectedStatus'=>$conds['status_filter'] ,
                'selectedPaymentMethod'=>$conds['payment_method'] ,
                'selectedDate'=> $conds['selected_date'],
            ];
        }
        else
        {
            $dataArr = [
                // 'showCoreAndCustomFieldArr' => $columnProps,
                // 'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                // 'categories' => $categories,
                // 'subcategories' => $subcategories,
                'orderTransactionReports' => $changedProductObj,
                'payment_methods' => $payment_methods,
                'statusList' => $statusList,
                'search'=>$conds['searchterm'] ,
                'selectedStatus'=>$conds['status_filter'] ,
                'selectedPaymentMethod'=>$conds['payment_method'] ,
                'selectedDate'=> $conds['selected_date'],
            ];
        }

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id) {
        $relations = ['currency', 'vendorPaymentStatus', 'owner', 'order', 'editor'];
        $checkPermission = $this->checkPermission($this->viewAnyAbility, VendorTransaction::class, "admin.index");
        $order_transaction = $this->getOrderTransactionDetail($id, $relations);

        $order_status = OrderStatus::where($this->orderStatusId, $order_transaction['order']['order_status_id'])
        ->when(null, function ($q, $null) {
        $q->with($null);
        })->first();

        $user_address = ShippingAndBilling::where(ShippingAndBilling::orderId , $order_transaction['order']['id'])->first();

        $dataArr = [
            'checkPermission' => $checkPermission,
            "order_transaction" => $order_transaction,
            "order_status" => $order_status,
            'user_address' => $user_address
        ];
        //dd($dataArr);
        return renderView(self::editPath, $dataArr);
    }

    public function getOrderTransactionDetail($id, $relation=null)
    {
        $order_transaction_detail = VendorTransaction::where($this->orderTransactionId, $id)
            ->when($relation, function ($q, $relation) {
            $q->with($relation);
        })->first();
        return $order_transaction_detail;
    }

    public function OrderTransactionReportCsvExport(){
        $filename = newFileNameForExport($this->csvFileName);
        return (new TransactionsExport())->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

}
