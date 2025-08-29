<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Controllers\Vendor;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Services\VendorService;
use Illuminate\Support\Facades\DB;
use Modules\StoreFront\VendorPanel\Http\Requests\UpdateVendorRequest;

class MyVendorController extends Controller
{
    const parentPath = "Pages/vendor/views/vendor/";
    const indexPath = self::parentPath . 'Index';
    const createPath = self::parentPath . 'Create';
    const editPath = self::parentPath . 'Edit';
    const indexRoute = "vendor_item.index";

    protected $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    public function index()
    {
        updateSessionLastActivity();

        $vendorId = getVendorIdFromSession();
        if($vendorId != null){

            $dataArr = $this->vendorService->show($vendorId);

            return renderView(self::editPath, $dataArr);

        }else{
            return redirect()->back();
        }


        // check permission
        // $checkPermission = $dataArr["checkPermission"];
        // if (!empty($checkPermission)){
        //     return $checkPermission;
        // }


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
}
