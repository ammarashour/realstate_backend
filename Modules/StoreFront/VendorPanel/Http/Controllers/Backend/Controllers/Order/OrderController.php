<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Order;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\OrderService;

class OrderController extends PsController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorOrderListModule, ps_constant::readPermission, $vendorId);

        return $this->orderService->index($request);
    }

    public function edit($id)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorOrderListModule, ps_constant::updatePermission, $vendorId);

        return $this->orderService->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->orderService->update($request, $id);
    }

    public function destroy($id)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorOrderListModule, ps_constant::deletePermission, $vendorId);

        return $this->orderService->destroy($id);
    }
}
