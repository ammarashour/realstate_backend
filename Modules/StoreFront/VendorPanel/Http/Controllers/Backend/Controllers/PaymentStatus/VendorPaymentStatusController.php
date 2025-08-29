<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\PaymentStatus;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\VendorPaymentStatusService;
use Modules\StoreFront\VendorPanel\Http\Requests\StoreVendorPaymentStatusRequest;
use Modules\StoreFront\VendorPanel\Http\Requests\UpdateVendorPaymentStatusRequest;

class VendorPaymentStatusController extends PsController
{
    protected $vendorPaymentStatusService;

    public function __construct(VendorPaymentStatusService $vendorPaymentStatusService)
    {
        parent::__construct();

        $this->vendorPaymentStatusService = $vendorPaymentStatusService;
    }

    public function index(Request $request)
    {
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorPaymentStatusModule, ps_constant::readPermission, $vendorId);

        return $this->vendorPaymentStatusService->index($request);
    }

    public function create()
    {
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorPaymentStatusModule, ps_constant::createPermission, $vendorId);

        return $this->vendorPaymentStatusService->create();
    }

    public function store(StoreVendorPaymentStatusRequest $request)
    {
        return $this->vendorPaymentStatusService->store($request);
    }

    public function edit($vendor_id, $id)
    {
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorPaymentStatusModule, ps_constant::updatePermission, $vendorId);

        return $this->vendorPaymentStatusService->edit($vendor_id, $id);
    }

    public function update(UpdateVendorPaymentStatusRequest $request)
    {
        return $this->vendorPaymentStatusService->update($request);
    }

    public function statusChange($vendor_id, $id){
        return $this->vendorPaymentStatusService->makePublishOrUnpublish($vendor_id, $id);
    }

    public function destroy($vendor_id, $id)
    {
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorPaymentStatusModule, ps_constant::deletePermission, $vendorId);

        return $this->vendorPaymentStatusService->destroy($vendor_id, $id);
    }
}
