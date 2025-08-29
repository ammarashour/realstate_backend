<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Entities\VendorPaymentStatus;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPaymentStatus\VendorPaymentStatusWithKeyResource;

class VendorPaymentStatusService extends PsService
{
    const parentPath = "Pages/vendor/views/payment_status/";
    const indexPath = self::parentPath . 'Index';
    const createPath = self::parentPath . 'Create';
    const editPath = self::parentPath . 'Edit';
    const indexRoute = "vendor_payment_status.index";
    const createRoute = "vendor_payment_status.create";
    const editRoute = "vendor_payment_status.edit";

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

        $paymentStatuses = VendorPaymentStatusWithKeyResource::collection($this->getPaymentStatuses($vendorId, null, null, null, $conds, false, $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::payment, $this->controlFieldArr());

        if($conds['order_by'])
        {
            $dataArr = [
                'showPaymentCols' => $columnAndColumnFilter[ps_constant::showCoreField],
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
                'paymentStatuses' => $paymentStatuses,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
            ];
        }
        else
        {
            $dataArr = [
                'showPaymentCols' => $columnAndColumnFilter[ps_constant::showCoreField],
                'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
                'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
                'paymentStatuses' => $paymentStatuses,
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
        $count = $this->getPaymentStatuses($vendorId, null, null, null, null, true, null)->count();

        DB::beginTransaction();

        try {
            // save paymentStatus
            $paymentStatus = new VendorPaymentStatus();
            $paymentStatus->fill($request->validated());
            $paymentStatus->id = $count+1;
            $paymentStatus->vendor_id = $vendorId;
            $paymentStatus->is_ps_default = 0;
            $paymentStatus->added_user_id = Auth::user()->id;
            $paymentStatus->save();

            DB::commit();
            return redirectView(self::indexRoute, $paymentStatus);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirectView(self::createRoute, $e->getMessage(), Constants::danger);
        }
    }

    public function edit($vendorId, $id)
    {

        $paymentStatus = $this->getPaymentStatus($id, $vendorId);

        $dataArr = [
            'paymentStatus' => $paymentStatus
        ];

        return renderView(self::editPath, $dataArr);
    }

    public function update($request)
    {
        DB::beginTransaction();

        try {
            // update paymentStatus
            $paymentStatus = $this->getPaymentStatus($request->id, $request->vendor_id);
            $paymentStatus->updated_user_id = Auth::user()->id;
            $paymentStatus->update($request->validated());

            DB::commit();
            return redirectView(self::indexRoute, $paymentStatus);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirectView(self::editRoute, $e->getMessage(), Constants::danger, [$request->vendor_id, $request->id]);
        }
    }

    public function makePublishOrUnpublish($vendorId, $id)
    {
        $paymentStatus = $this->getPaymentStatus($id, $vendorId);
        if($paymentStatus->status == Constants::publish){
            $paymentStatus->status = Constants::unPublish;
        }else{
            $paymentStatus->status = Constants::publish;
        }
        $paymentStatus->updated_user_id = Auth::user()->id;
        $paymentStatus->update();

        return redirectView(self::indexRoute, __('core__be_status_updated'));
    }

    public function destroy($vendorId, $id)
    {

        $vendorPaymentStatus = $this->getPaymentStatus($id, $vendorId);
        $vendorPaymentStatus->delete();

        $dataArr = [
             "msg" =>  __('core__be_delete_success', ['attribute' => $vendorPaymentStatus->name]),
             "flag" => Constants::success
         ];

         return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }

    public function getPaymentStatuses($vendorId, $relations = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if(isset($conds['order_by'])){
            $sort = $conds['order_by'];
        }
        $paymentStatuses = VendorPaymentStatus::where(VendorPaymentStatus::vendorId, $vendorId)
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
                $query->orderBy(VendorPaymentStatus::status, 'desc');
            });
        if ($pagPerPage){
            $paymentStatuses = $paymentStatuses->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination){
            $paymentStatuses = $paymentStatuses->get();
        }

        return $paymentStatuses;
    }

    public function searching($query, $conds){

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(VendorPaymentStatus::tableName . '.' . VendorPaymentStatus::name, 'like', '%' . $search . '%');
                $query->orWhere(VendorPaymentStatus::tableName . '.' . VendorPaymentStatus::description, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['status']) && $conds['status']) {
            $query->where(VendorPaymentStatus::status, $conds['status']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'name') {
                $query->orderBy(VendorPaymentStatus::name, $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            $query->orderBy(VendorPaymentStatus::tableName . '.' . VendorPaymentStatus::CREATED_AT, 'desc');
        }

        return $query;
    }

    public function getPaymentStatus($id, $vendorId, $relations = null)
    {
        $paymentStatus = VendorPaymentStatus::where([
            VendorPaymentStatus::id => $id,
            VendorPaymentStatus::vendorId => $vendorId
        ])->when($relations, function($query, $relations) {
            $query->with($relations);
        })
        ->first();

        return $paymentStatus;
    }

    public function setPaymentStatuses($vendorId)
    {
        $paymentStatus = $this->getPaymentStatuses($vendorId, null, null, null, null, true, null);
        if(empty($paymentStatus->toArray())){
            $vendorPaymentStatuses = [
                [
                    "id" => 1,
                    "name" => "Unpaid",
                    "description" => "Unpaid",
                    "colour" => "#DC2626",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 2,
                    "name" => "Paid",
                    "description" => "Paid",
                    "colour" => "#059669",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 3,
                    "name" => "Rejected by Vendor",
                    "description" => "Rejected by Vendor",
                    "colour" => "#cc0081",
                    "added_user_id" => 1,
                ],
                [
                    "id" => 4,
                    "name" => "Refunded",
                    "description" => "Refunded",
                    "colour" => "#3600cc",
                    "added_user_id" => 1,
                ],
            ];

            foreach($vendorPaymentStatuses as $vendorPaymentStatus){
                $vendorPaymentStatusObj = new VendorPaymentStatus();
                $vendorPaymentStatusObj->id = $vendorPaymentStatus['id'];
                $vendorPaymentStatusObj->name = $vendorPaymentStatus['name'];
                $vendorPaymentStatusObj->vendor_id = $vendorId;
                $vendorPaymentStatusObj->colour = $vendorPaymentStatus['colour'];
                $vendorPaymentStatusObj->status = 1;
                $vendorPaymentStatusObj->is_ps_default = 1;
                $vendorPaymentStatusObj->description = $vendorPaymentStatus['description'];
                $vendorPaymentStatusObj->added_user_id = $vendorPaymentStatus['added_user_id'];
                $vendorPaymentStatusObj->save();
            }
            return $this->getPaymentStatuses($vendorId);
        }
        return $paymentStatus;
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


