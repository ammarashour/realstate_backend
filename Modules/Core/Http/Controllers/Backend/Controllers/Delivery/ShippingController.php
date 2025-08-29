<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Delivery;


use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\Delivery\StoreShippingRequest;
use Modules\Core\Http\Requests\Delivery\UpdateShippingRequest;
use App\Http\Controllers\PsController;
use Modules\Core\Http\Services\ShopService;
use App\Http\Contracts\Delivery\ShippingServiceInterface;

class ShippingController extends PsController
{
    private const parentPath = "shipping/";
    private const indexPath = self::parentPath . "Index";
    private const createPath = self::parentPath . "Create";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "shipping.index";
    private const createRoute = "shipping.create";
    private const editRoute = "shipping.edit";

    public function __construct(
        protected ShippingServiceInterface $shippingService,
        protected ShopService $shopService
    ) {
        parent::__construct();
    }

    public function index()
    {
        $dataArr = $this->prepareIndexData();
        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        $dataArr = $this->prepareCreateData();
        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreShippingRequest $request)
    {
        try {
            $validateData = $request->validated();
            $this->shippingService->save($validateData);
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        $dataArr = $this->prepareEditData($id);
        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateShippingRequest $request, $id)
    {
        try {
            $validateData = $request->validated();
            $this->shippingService->update($id, $validateData);
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $shipping = $this->shippingService->get($id);

            $this->handlePermissionWithModel($shipping, Constants::deleteAbility);

            $dataArr = $this->shippingService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $shipping = $this->shippingService->get($id);

            $this->handlePermissionWithModel($shipping, Constants::editAbility);

            $status = $this->prepareStatusData($shipping);

            $this->shippingService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {
        $shippings = $this->shippingService->getAll();
        return [
            'shippings' => $shippings,
        ];
    }

    private function prepareCreateData()
    {
        $shops = $this->shopService->getShops(null, Constants::publish);
        $dataArr =  [
            'shops' => $shops
        ];
        return $dataArr;
    }

    private function prepareEditData($id)
    {
        $shops = $this->shopService->getShops(null, Constants::publish);
        $shipping = $this->shippingService->get($id);
        $dataArr = [
            'shops' => $shops,
            'shipping' => $shipping
        ];
        return $dataArr;
    }
    private function prepareStatusData($shipping)
    {
        return $shipping->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }
}
