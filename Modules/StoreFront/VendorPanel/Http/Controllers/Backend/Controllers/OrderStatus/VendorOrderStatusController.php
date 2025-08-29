<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\OrderStatus;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Requests\StoreOrderStatusRequest;
use Modules\StoreFront\VendorPanel\Http\Requests\UpdateOrderStatusRequest;
use Modules\StoreFront\VendorPanel\Http\Services\VendorOrderStatusService;

class VendorOrderStatusController extends PsController
{
    protected $vendorOrderStatusService;

    public function __construct(VendorOrderStatusService $vendorOrderStatusService)
    {
        parent::__construct();
        
        $this->vendorOrderStatusService = $vendorOrderStatusService;
    }

    public function index(Request $request)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorOrderStatusModule, ps_constant::readPermission, $vendorId);

        return $this->vendorOrderStatusService->index($request);
    }

    public function create()
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorOrderStatusModule, ps_constant::createPermission, $vendorId);

        return $this->vendorOrderStatusService->create();
    }

    public function store(StoreOrderStatusRequest $request)
    {
        return $this->vendorOrderStatusService->store($request);
    }

    public function edit($vendor_id, $id)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorOrderStatusModule, ps_constant::updatePermission, $vendorId);

        return $this->vendorOrderStatusService->edit($vendor_id, $id);
    }

    public function update(UpdateOrderStatusRequest $request)
    {
        return $this->vendorOrderStatusService->update($request);
    }

    public function statusChange($vendor_id, $id){
        return $this->vendorOrderStatusService->makePublishOrUnpublish($vendor_id, $id);
    }

    public function destroy($vendor_id, $id)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorOrderStatusModule, ps_constant::deletePermission, $vendorId);

        return $this->vendorOrderStatusService->destroy($vendor_id, $id);
    }
}
