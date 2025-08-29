<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Subscription;

// use Modules\Core\Http\Services\SubscriptionHistoryService;
use Modules\StoreFront\VendorPanel\Http\Services\SubscriptionHistoryService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class SubscriptionHistoryController extends Controller
{
    const parentPath = "Pages/vendor/views/subscriptionHistory/";
    const indexPath = self::parentPath . 'Index';

    protected $SubscriptionHistoryService;

    public function __construct( SubscriptionHistoryService $SubscriptionHistoryService)
    {
        $this->SubscriptionHistoryService = $SubscriptionHistoryService;

    }

    public function index(Request $request)
    {

        $dataArr = $this->SubscriptionHistoryService->index($request);
        // check permission
        // $checkPermission = $dataArr["checkPermission"];
        // if ($checkPermission == false) {
        //     return redirect()->back();
        // }
        // dd($dataArr);

        return renderView(self::indexPath, $dataArr);
    }




}
