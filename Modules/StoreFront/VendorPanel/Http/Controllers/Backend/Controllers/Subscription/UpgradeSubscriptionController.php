<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Subscription;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\VendorService;
use Modules\Core\Http\Services\UpgradeSubscriptionService;
use Modules\StoreFront\VendorPanel\Http\Requests\UpdateVendorRequest;

class UpgradeSubscriptionController extends PsController
{
    const parentPath = "Pages/vendor/views/upgrade_subscription/";
    const indexPath = self::parentPath . 'Index';
    const createPath = self::parentPath . 'Create';
    const editPath = self::parentPath . 'Edit';
    const indexRoute = "upgrade_subscription.index";

    protected $vendorService, $upgradeSubscriptionService;

    public function __construct(VendorService $vendorService, UpgradeSubscriptionService $upgradeSubscriptionService)
    {
        parent::__construct();

        $this->vendorService = $vendorService;
        $this->upgradeSubscriptionService = $upgradeSubscriptionService;
    }

    public function index(Request $request)
    {
        // check permission start
        $vendorId = getVendorIdFromSession();
        $this->handleVendorPermission(Constants::vendorItemModule, ps_constant::readPermission, $vendorId);

        $dataArr = $this->upgradeSubscriptionService->index($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        $dataArr = $this->vendorService->show($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateVendorRequest $request, $id)
    {
        return $this->vendorService->update($id, $request);
    }

    public function store(Request $request)
    {
        $dataArr = $this->upgradeSubscriptionService->upgradeSubscription($request);

        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }
}
