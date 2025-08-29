<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Entities\OrderStatus;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPaymentStatus\VendorPaymentStatusWithKeyResource;

class VendorOrderStatusService extends PsService
{
    const parentPath = "Pages/vendor/views/order_status/";
    const indexPath = self::parentPath . 'Index';
    const createPath = self::parentPath . 'Create';
    const editPath = self::parentPath . 'Edit';
    const indexRoute = "vendor_order_status.index";
    const createRoute = "vendor_order_status.create";
    const editRoute = "vendor_order_status.edit";

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

        $orderStatuses = VendorPaymentStatusWithKeyResource::collection($this->getOrderStatuses($vendorId, null, null, null, $conds, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::payment, $this->controlFieldArr());

        if($conds['order_by'])
        {
            $dataArr = [
                'showOrderStatusCols' => $columnAndColumnFilter[ps_constant::showCoreField],
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
                'orderStatuses' => $orderStatuses,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
            ];
        }
        else
        {
            $dataArr = [
                'showOrderStatusCols' => $columnAndColumnFilter[ps_constant::showCoreField],
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
                'orderStatuses' => $orderStatuses,
                'search'=>$conds['searchterm'],
            ];
        }

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        return renderView(self::createPath);
    }

    public function store($request)
    {
        $vendorId = getVendorIdFromSession();
        $count = $this->getOrderStatuses($vendorId, null, null, null, null, true, null)->count();

        DB::beginTransaction();

        try {
            // save orderStatus
            $orderStatus = new OrderStatus();
            $orderStatus->fill($request->validated());
            $orderStatus->id = $count+1;
            $orderStatus->vendor_id = $vendorId;
            $orderStatus->is_ps_default = 0;
            $orderStatus->added_user_id = Auth::user()->id;
            $orderStatus->save();

            DB::commit();
            return redirectView(self::indexRoute, $orderStatus);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirectView(self::createRoute, $e->getMessage(), Constants::danger);
        }
    }

    public function edit($vendorId, $id)
    {
        $vendorId = getVendorIdFromSession();

        $orderStatus = $this->getOrderStatus($id, $vendorId);

        $dataArr = [
            'orderStatus' => $orderStatus
        ];

        return renderView(self::editPath, $dataArr);
    }

    public function update($request)
    {
        DB::beginTransaction();

        try {
            // update orderStatus
            $orderStatus = $this->getOrderStatus($request->id, $request->vendor_id);
            $orderStatus->updated_user_id = Auth::user()->id;
            $orderStatus->update($request->validated());

            DB::commit();
            return redirectView(self::indexRoute, $orderStatus);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirectView(self::editRoute, $e->getMessage(), Constants::danger, [$request->vendor_id, $request->id]);
        }
    }

    public function makePublishOrUnpublish($vendorId, $id)
    {
        $orderStatus = $this->getOrderStatus($id, $vendorId);
        if($orderStatus->status == Constants::publish){
            $orderStatus->status = Constants::unPublish;
        }else{
            $orderStatus->status = Constants::publish;
        }
        $orderStatus->updated_user_id = Auth::user()->id;
        $orderStatus->update();

        return redirectView(self::indexRoute, __('core__be_status_updated'));
    }

    public function destroy($vendorId, $id)
    {
        $vendorId = getVendorIdFromSession();

        $vendorPaymentStatus = $this->getOrderStatus($id, $vendorId);
        $vendorPaymentStatus->delete();

        $dataArr = [
             "msg" =>  __('core__be_delete_success', ['attribute' => $vendorPaymentStatus->name]),
             "flag" => Constants::success
         ];

         return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }

    public function getOrderStatuses($vendorId, $relations = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if(isset($conds['order_by'])){
            $sort = $conds['order_by'];
        }
        $orderStatuses = OrderStatus::where(OrderStatus::vendorId, $vendorId)
            ->when($relations, function ($query, $relations) {
                $query->with($relations);
            })->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when(empty($sort), function ($query, $conds){
                $query->orderBy(OrderStatus::status, 'desc');
            });
        if ($pagPerPage){
            $orderStatuses = $orderStatuses->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination){
            $orderStatuses = $orderStatuses->get();
        }

        return $orderStatuses;
    }

    public function searching($query, $conds){

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(OrderStatus::tableName . '.' . OrderStatus::name, 'like', '%' . $search . '%');
                $query->orWhere(OrderStatus::tableName . '.' . OrderStatus::description, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['status']) && $conds['status']) {
            $query->where(OrderStatus::status, $conds['status']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'name') {
                $query->orderBy(OrderStatus::name, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(OrderStatus::tableName . '.' . OrderStatus::CREATED_AT, 'desc');
        }

        return $query;
    }

    public function getOrderStatus($id, $vendorId, $relations = null)
    {
        $orderStatus = OrderStatus::where([
            OrderStatus::id => $id,
            OrderStatus::vendorId => $vendorId
        ])->when($relations, function($query, $relations) {
            $query->with($relations);
        })
        ->first();

        return $orderStatus;
    }

    public function setOrderStatuses($vendorId)
    {
        $orderStatus = $this->getOrderStatuses($vendorId, null, null, null, null, true, null);

        if(empty($orderStatus->toArray())){
            $orderStatuses = [
                [
                    "id" => 1,
                    "name" => "Pending",
                    "description" => "Pending",
                    "colour" => "#F59E0B",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 2,
                    "name" => "Approved",
                    "description" => "Approved",
                    "colour" => "#059669",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 3,
                    "name" => "Delivering",
                    "description" => "Delivering",
                    "colour" => "#3B82F6",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 4,
                    "name" => "Delivered",
                    "description" => "Delivered",
                    "colour" => "#059669",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 5,
                    "name" => "Cancelled",
                    "description" => "Cancelled",
                    "colour" => "#e600ac",
                    "added_user_id" => 1,
                ],
            ];

            foreach($orderStatuses as $orderStatus){
                $orderStatusObj = new OrderStatus();
                $orderStatusObj->id = $orderStatus['id'];
                $orderStatusObj->name = $orderStatus['name'];
                $orderStatusObj->vendor_id = $vendorId;
                $orderStatusObj->colour = $orderStatus['colour'];
                $orderStatusObj->status = 1;
                $orderStatusObj->is_ps_default = 1;
                $orderStatusObj->description = $orderStatus['description'];
                $orderStatusObj->added_user_id = $orderStatus['added_user_id'];
                $orderStatusObj->save();
            }
            return $this->getOrderStatuses($vendorId);
        }
        return $orderStatus;
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


