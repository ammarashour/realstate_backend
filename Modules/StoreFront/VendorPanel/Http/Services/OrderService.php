<?php
namespace Modules\StoreFront\VendorPanel\Http\Services;

use stdClass;
use App\Models\User;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use App\Config\ps_config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Entities\Order;
use Modules\StoreFront\VendorPanel\Entities\OrderItem;
use Modules\StoreFront\VendorPanel\Entities\OrderStatus;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\StoreFront\VendorPanel\Entities\VendorTransaction;
use Modules\StoreFront\VendorPanel\Entities\ShippingAndBilling;
use Modules\StoreFront\VendorPanel\Entities\VendorPaymentStatus;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\Order\OrderWithKeyResource;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Order\OrderHistoryApiResource;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Order\OrderSummaryApiResource;

class OrderService extends PsService
{
    const parentPath = "Pages/vendor/views/order_list/";
    const indexPath = self::parentPath . 'Index';
    const createPath = self::parentPath . 'Create';
    const editPath = self::parentPath . 'Edit';
    const indexRoute = "vendor_order_list.index";
    const createRoute = "vendor_order_list.create";
    const editRoute = "vendor_order_list.edit";

    protected $userAccessApiTokenService, $vendorPaymentStatusService, $vendorOrderStatusService;

    public function __construct(UserAccessApiTokenService $userAccessApiTokenService, VendorPaymentStatusService $vendorPaymentStatusService, VendorOrderStatusService $vendorOrderStatusService)
    {
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->vendorPaymentStatusService = $vendorPaymentStatusService;
        $this->vendorOrderStatusService = $vendorOrderStatusService;
    }

    public function getOrder($id = null, $relation = null)
    {
        $order = Order::when($id, function($query, $id){
            $query->where(Order::id, $id);
        })
        ->when($relation, function($query, $relation){
            $query->with($relation);
        })
        ->first();

        return $order;
    }

    public function getOrderSummaryFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        if (empty($userAccessApiToken)){
            $msg = __('shipping_and_billing__api_store_info_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, Constants::forbiddenStatusCode);
        }
        /// check permission end

        $orderId = $request->order_id;
        $getOrder = $this->getOrder($orderId);
        $vendorId = $getOrder->vendor_id;
        $relation = [
            'orderItems' => ['item'],
            'shippingAndBilling',
            'vendorTransaction' => [
                'vendorPaymentStatus'  => function($query) use ($vendorId){
                    $query->where("vendor_id", $vendorId);
                },
                'currency', 'vendor'
            ]
        ];

        $getOrder = $this->getOrder($orderId, $relation);
        $data = new OrderSummaryApiResource($getOrder);
        return responseDataApi($data);

    }

    public function index($request)
    {
        $vendorId = getVendorIdFromSession();

        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['order_by']= null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;
        if($request->sort_field){
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $relations = ['vendorTransaction' => function($q) use($vendorId) {
            $q->with(['currency','vendorPaymentStatus' => function($q) use($vendorId){
                $q->where('vendor_id', $vendorId);
            }]);
        }, 'orderStatus'];
        $orders = OrderWithKeyResource::collection($this->getOrders($vendorId, $relations, null, null, $conds, false, $row));
        $vendorPaymentStatuses = $this->vendorPaymentStatusService->getPaymentStatuses($vendorId, null, null, null, null, true, null);
        if($conds['order_by'])
        {
            $dataArr = [
                'orders' => $orders,
                'vendorPaymentStatuses' => $vendorPaymentStatuses,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
            ];
        }
        else
        {
            $dataArr = [
                'orders' => $orders,
                'vendorPaymentStatuses' => $vendorPaymentStatuses,
                'search'=>$conds['searchterm'],
            ];
        }

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        $vendorId = getVendorIdFromSession();

        $relations = [
            'orderItems' => function($q) {
                $q->with(['item'=>function($q){
                    $q->with(['cover']);
                }]);
            },
            'shippingAndBilling',
            'vendorTransaction' => function($q) use($vendorId) {
                $q->with(['currency','vendorPaymentStatus' => function($q) use($vendorId){
                    $q->where('vendor_id', $vendorId);
                }]);
            }
        ];

        $order = new OrderWithKeyResource($this->getOrder($id, $relations));
        $paymentStatuses = $this->vendorPaymentStatusService->getPaymentStatuses($vendorId, null, null, null, null, true, null);
        $orderStatuses = $this->vendorOrderStatusService->getOrderStatuses($vendorId, null, null, null, null, true, null);

        $dataArr = [
            'order' => $order,
            'paymentStatuses' => $paymentStatuses,
            'orderStatuses' => $orderStatuses
        ];

        return renderView(self::editPath, $dataArr);
    }

    public function update($request, $id)
    {
        $vendorId = getVendorIdFromSession();
        $relations = [
            'vendor',
            'shippingAndBilling',
            'vendorTransaction' => function($q) use($vendorId) {
                $q->with(['currency','vendorPaymentStatus' => function($q) use($vendorId){
                    $q->where('vendor_id', $vendorId);
                }]);
            }
        ];

        DB::beginTransaction();

        try {
            $order = $this->getOrder($id, $relations);
            $order->order_status_id = $request->order_status_id;
            $order->updated_user_id = Auth::user()->id;
            $order->update();

            $vendorTransaction = VendorTransaction::where(VendorTransaction::orderId, $id)->first();
            $vendorTransaction->vendor_payment_status_id = $request->payment_status_id;
            $vendorTransaction->updated_user_id = Auth::user()->id;
            $vendorTransaction->update();

            sendBuyerForOrderStatusMail($order);

            DB::commit();
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back();
        }
    }

    public function getOrders($vendorId, $relations = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if(isset($conds['order_by'])){
            $sort = $conds['order_by'];
        }
        $orders = Order::
                        // join(OrderItem::tableName, OrderItem::tableName.'.'.OrderItem::orderId, Order::tableName.'.'.Order::id)
                        // ->join(VendorTransaction::tableName, VendorTransaction::tableName.'.'.VendorTransaction::orderId, Order::tableName.'.'.Order::id)
                        // ->select(Order::tableName.'.*', OrderItem::tableName.'.*')
                        when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort, $vendorId) {
                            if ($sort == 'owner_id@@name') {
                                $q->join(User::tableName, User::tableName.'.'.User::id, '=', Order::tableName.'.'.Order::userId);
                                $q->select(User::tableName.'.'.User::name." as owner_name", Order::tableName.'.*');
                            }
                            if ($sort == 'order_status_id@@name') {
                                $q->join(OrderStatus::tableName, OrderStatus::tableName.'.'.OrderStatus::id, '=', Order::tableName.'.'.Order::orderStatusId);
                                $q->where([OrderStatus::tableName.'.'.OrderStatus::vendorId => $vendorId]);
                                $q->select(OrderStatus::tableName.'.'.OrderStatus::name." as order_status_name", Order::tableName.'.*');
                            }
                            if ($sort == 'vendor_payment_status_id@@name') {
                                $q->join(VendorTransaction::tableName, VendorTransaction::tableName.'.'.VendorTransaction::orderId, '=', Order::tableName.'.'.Order::id);
                                $q->join(VendorPaymentStatus::tableName, VendorPaymentStatus::tableName.'.'.VendorPaymentStatus::id, '=', VendorTransaction::tableName.'.'.VendorTransaction::vendorPaymentStatusId);
                                $q->where([VendorPaymentStatus::tableName.'.'.VendorPaymentStatus::vendorId => $vendorId]);
                                $q->select(VendorPaymentStatus::tableName.'.'.VendorPaymentStatus::name." as vendor_payment_status_name", Order::tableName.'.*');
                            }
                        })
                        ->when($relations, function ($query, $relations) {
                            $query->with($relations);
                        })
                        ->when($limit, function ($query, $limit) {
                            $query->limit($limit);
                        })
                        ->when($conds, function ($query, $conds) {
                            $query = $this->searching($query, $conds);
                        })
                        ->when($offset, function ($query, $offset) {
                            $query->offset($offset);
                        })
                        ->when(empty($sort), function ($query, $conds){
                            $query->orderBy(Order::tableName.'.'.Order::id, 'desc');
                        })
                        ->where([Order::tableName.'.'.Order::vendorId => $vendorId, Order::tableName.'.'.Order::isPaymentFail => 0]);
                        if ($pagPerPage){
                            $orders = $orders->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                        } elseif ($noPagination){
                            $orders = $orders->get();
                        }
        return $orders;
    }

    public function searching($query, $conds){

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->join(User::tableName, User::tableName.'.'.User::id,Order::tableName.'.'.Order::userId);
            $query->where(function ($query) use ($search) {
                $query->where(User::tableName . '.' . User::name, 'like', '%' . $search . '%');
                $query->orWhere(Order::tableName . '.' . Order::orderCode, 'like', '%' . $search . '%');
            });
        }

        // order status
        if(isset($conds['order_status_id']) && $conds['order_status_id']){
            $query->where(Order::orderStatusId, $conds['order_status_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy(Order::id, $conds['order_type']);
            } elseif ($conds['order_by'] == 'order_code') {
                $query->orderBy(Order::orderCode, $conds['order_type']);
            } elseif ($conds['order_by'] == 'owner_id@@name') {
                $query->orderBy('owner_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'quantity') {
                $query->orderBy('quantity', $conds['order_type']);
            } elseif ($conds['order_by'] == 'total_price') {
                $query->orderBy('total_price', $conds['order_type']);
            } elseif ($conds['order_by'] == 'order_date') {
                $query->orderBy('order_date', $conds['order_type']);
            } elseif ($conds['order_by'] == 'added_date') {
                $query->orderBy('added_date', $conds['order_type']);
            } elseif ($conds['order_by'] == 'order_status_id@@name') {
                $query->orderBy('order_status_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'vendor_payment_status_id@@name') {
                $query->orderBy('vendor_payment_status_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(Order::tableName . '.' . Order::CREATED_AT, 'desc');
        }

        return $query;
    }

    public function destroy($id)
    {
        $vendorId = getVendorIdFromSession();

        $order = $this->getOrder($id);
        $orderItems = OrderItem::where(OrderItem::orderId, $id)->get();
        $vendorTransactions = VendorTransaction::where(VendorTransaction::orderId, $id)->get();
        $shippingAndBilling = ShippingAndBilling::where(ShippingAndBilling::orderId, $id)->first();
        $shippingAndBilling->delete();

        foreach($vendorTransactions as $transaction){
            $transaction->delete();
        }
        foreach($orderItems as $item){
            $item->delete();
        }
        $order->delete();

        $dataArr = [
            "msg" =>  __('core__be_delete_success', ['attribute' => $order->order_code]),
            "flag" => Constants::success
        ];

        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }

    public function getOrderHistoryFromApi($userId, $relations = null, $conds = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null){
        $orders = Order::where([Order::userId => $userId, Order::isPaymentFail => 0])
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            });
            if ($pagPerPage){
                $orders = $orders->paginate($pagPerPage)->onEachSide(1)->withQueryString();
            }else if($noPagination){
                $orders = $orders->get();
            }

        return $orders;
    }

    public function searchFromApi($request)
    {
        $userId = $request->login_user_id;
        $limit = $request->limit;
        $offset = $request->offset;
        $perPage = ps_config::pagPerPage;
        $noPagination = false;
        if(isset($request->noPagination) && $request->noPagination){
            $perPage = null;
            $noPagination = $request->noPagination;
        }
        $conds = [];
        $relations = [
            'vendor',
            'shippingAndBilling',
            'vendorTransaction' => function($q) {
                $q->with(['currency']);
            },
            'orderItems' => function($q) {
                $q->with(['item'=>function($q){
                    $q->with(['cover']);
                }]);
            },
        ];
        if(isset($request->order_status)){
            $conds['order_status_id'] = $request->order_status;
        }
        $conds['order_by'] = $request->order_by;
        $conds['order_type'] = $request->order_type;

        $orders = $this->getOrderHistoryFromApi($userId, $relations, $conds, $limit, $offset, $noPagination, $perPage);
        $totalOrders = Order::where([Order::userId => $userId, Order::isPaymentFail => 0])->get();
        $data = [];
        if($noPagination){
            $data = OrderHistoryApiResource::collection($orders);
        }else{
            $data = [
                'all_order_count' => $totalOrders->count(),
                'pending_order_count' => $totalOrders->where('order_status_id', constants::orderPendingStatus)->count(),
                'approved_order_count' => $totalOrders->where('order_status_id', constants::orderApprovedStatus)->count(),
                'delivering_order_count' => $totalOrders->where('order_status_id', constants::orderDeliveringStatus)->count(),
                'delivered_order_count' => $totalOrders->where('order_status_id', constants::orderDeliveredStatus)->count(),
                'order_history' => OrderHistoryApiResource::collection($orders),
                // 'order_history' => $orders,
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'from' => $orders->firstItem(),
                    'last_page' => $orders->lastPage(),
                    'path' => $orders->path(),
                    'per_page' => $orders->perPage(),
                    'to' => $orders->lastItem(),
                    'total' => $orders->total(),
                    'links' => $orders->toArray()['links']
                ],
            ];
        }


        return responseDataApi($data);
    }


    public function downloadOrderPDF($request){
        $orderId = $request->order_id;
        $relations = [
            'vendor', 'orderStatus',
            'shippingAndBilling',
            'vendorTransaction' => function($q) {
                $q->with(['currency','vendorPaymentStatus']);
            },
            'orderItems' => function($q) {
                $q->with(['item'=>function($q){
                    $q->with(['cover']);
                }]);
            },
        ];
        $order = $this->getOrder($orderId, $relations);
        if($order){
            // dd($order->toArray());
            $pdf = PDF::loadView('order.order', compact('order'));
            $file = 'order_'.$order->order_code.'.pdf';

            return $pdf->download($file);
        }

    }

}
