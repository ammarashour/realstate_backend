<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Entities\VendorDeliverySetting;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorPaymentStatus\VendorPaymentStatusWithKeyResource;
use Modules\StoreFront\VendorPanel\Transformers\Backend\Model\VendorDeliverySetting\VendorDeliverySettingWithKeyResource;

class VendorDeliverySettingService extends PsService
{
    const parentPath = "Pages/vendor/views/delivery_setting/";
    const indexPath = self::parentPath . 'Index';
    const createPath = self::parentPath . 'Create';
    const editPath = self::parentPath . 'Edit';
    const indexRoute = "vendor_delivery_setting.index";
    const createRoute = "vendor_delivery_setting.create";
    const editRoute = "vendor_delivery_setting.edit";

    public function index($request)
    {
        $vendorId = getVendorIdFromSession();

        $deliverySetting = $this->getDeliverySetting($vendorId);

        $dataArr = [
            'vendor_delivery_setting' => $deliverySetting
        ];

        if($deliverySetting){
            return renderView(self::editPath, $dataArr);
        }
        return renderView(self::createPath);
    }

    public function getDeliverySetting($vendorId)
    {
        $deliverySetting = VendorDeliverySetting::where(VendorDeliverySetting::vendorId, $vendorId)->first();

        return $deliverySetting;
    }

    public function store($request)
    {
        $vendorId = getVendorIdFromSession();

        DB::beginTransaction();

        try {
            $deliverySetting = new VendorDeliverySetting();
            $deliverySetting->vendor_id = $vendorId;
            if($request->delivery_setting){
                $deliverySetting->delivery_setting = $request->delivery_setting;
            }

            if($request->delivery_charges){
                $deliverySetting->delivery_charges = $request->delivery_charges;
            }

            $deliverySetting->added_user_id = Auth::user()->id;
            $deliverySetting->save();

            DB::commit();
            return redirectView(self::indexRoute, $deliverySetting);
        } catch (\Throwable $e) {

            DB::rollBack();
            return redirectView(self::indexRoute, $e->getMessage(), Constants::danger);
        }
    }

    public function update($request, $id)
    {
        $vendorId = getVendorIdFromSession();
        DB::beginTransaction();

        try {
            $deliverySetting = $this->getDeliverySetting($vendorId);
            $deliverySetting->updated_user_id = Auth::user()->id;
            $deliverySetting->delivery_setting = $request->delivery_setting;
            $deliverySetting->delivery_charges = empty($request->delivery_charges) ? 0 : $request->delivery_charges;
            $deliverySetting->update();

            DB::commit();
            return redirectView(self::indexRoute, $deliverySetting);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirectView(self::indexRoute, $e->getMessage(), Constants::danger, $id);
        }
    }
}


