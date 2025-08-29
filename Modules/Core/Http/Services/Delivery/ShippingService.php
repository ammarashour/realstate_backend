<?php

namespace Modules\Core\Http\Services\Delivery;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Delivery\Shipping;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Services\ShopService;
use App\Http\Contracts\Delivery\ShippingServiceInterface;

class ShippingService extends PsService implements ShippingServiceInterface
{

    public function __construct() {}

    public function save($shippingData)
    {
        DB::beginTransaction();

        try {
            $shipping = $this->saveShipping($shippingData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $shipping;
    }

    public function update($id, $shippingData)
    {
        DB::beginTransaction();

        try {

            $shipping = $this->updateShipping($id, $shippingData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $shipping;
    }

    public function getAll($relation = null)
    {
        $subMenuGroups = Shipping::when($relation, function ($q, $relation) {
            $q->with($relation);
        })->latest()->get();
        return $subMenuGroups;
    }

    public function get($id = null)
    {
        $subMenuGroup = Shipping::when($id, function ($q, $id) {
            $q->where(Shipping::id, $id);
        })->first();
        return $subMenuGroup;
    }

    public function delete($id)
    {
        try {

            $name = $this->deleteShipping($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStausData($status);


            return $this->updateShipping($id, $status);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data preparation
    //-------------------------------------------------------------------
    private function prepareUpdateStausData($status)
    {
        return ['status' => $status];
    }
    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveShipping($shippingData)
    {
        $shipping = new Shipping();
        $shipping->fill($shippingData);
        $shipping->added_user_id = Auth::user()->id;
        $shipping->save();

        return $shipping;
    }
    private function updateShipping($id, $shippingData)
    {
        $shipping = $this->get($id);
        $shipping->updated_user_id = Auth::user()->id;
        $shipping->update($shippingData);
    }
    private function deleteShipping($id)
    {
        $shipping = $this->get($id);
        $name = $shipping->name;
        $shipping->delete();

        return $name;
    }
}
