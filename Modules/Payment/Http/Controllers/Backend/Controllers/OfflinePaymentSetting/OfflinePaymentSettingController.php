<?php

namespace Modules\Payment\Http\Controllers\Backend\Controllers\OfflinePaymentSetting;

use App\Config\ps_constant;
use App\Http\Contracts\Financial\OfflinePaymentSettingServiceInterface;
use App\Http\Contracts\Financial\PaymentAttributeServiceInterface;
use App\Http\Contracts\Financial\PaymentInfoServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Payment\Http\Requests\StoreOfflinePaymentSettingRequest;
use Modules\Payment\Http\Requests\UpdateOfflinePaymentSettingRequest;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Payment\Transformers\Backend\NoModel\OfflinePaymentSetting\OfflinePaymentSettingWithKeyResource;

class OfflinePaymentSettingController extends PsController
{
    private const parentPath = 'payment/offline_payment_setting/';

    private const indexPath = self::parentPath.'Index';

    private const createPath = self::parentPath.'Create';

    private const editPath = self::parentPath.'Edit';

    private const indexRoute = 'offline_payment_setting.index';

    private const createRoute = 'offline_payment_setting.create';

    private const editRoute = 'offline_payment_setting.edit';

    private const imageKey = 'icon';

    public function __construct(
        protected OfflinePaymentSettingServiceInterface $offlinePaymentSettingService,
        protected PaymentSettingService $paymentSettingService,
        protected PaymentAttributeServiceInterface $paymentAttributeService,
        protected PaymentInfoServiceInterface $paymentInfoService)
    {

        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->handlePermissionWithoutModel(Constants::offlinePaymentSettingModule, ps_constant::readPermission, Auth::id());
        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithoutModel(Constants::offlinePaymentSettingModule, ps_constant::createPermission, Auth::id());

        return renderView(self::createPath);
    }

    public function store(StoreOfflinePaymentSettingRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Get Image File
            $file = $request->file(self::imageKey);

            // Save Offline Payment Setting
            $this->offlinePaymentSettingService->save(offlinePaymentSettingData : $validData, offlinePaymentSettingImage : $file);

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }

    }

    public function edit($id)
    {
        $this->handlePermissionWithoutModel(Constants::offlinePaymentSettingModule, ps_constant::updatePermission, Auth::id());
        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);

    }

    public function update(UpdateOfflinePaymentSettingRequest $request, $id)
    {
        try {
            // Validate the request data
            $validData = $request->validated();
            // Get Image File
            $file = $request->file(self::imageKey);

            $this->offlinePaymentSettingService->update(
                id: $id,
                offlinePaymentSettingData: $validData);

            // Success and Redirect
            return redirectView(routeName: self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $this->handlePermissionWithoutModel(Constants::offlinePaymentSettingModule, ps_constant::deletePermission, Auth::id());

            $dataArr = $this->offlinePaymentSettingService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }

    }

    public function statusChange($id)
    {
        try {
            $this->handlePermissionWithoutModel(Constants::offlinePaymentSettingModule, ps_constant::updatePermission, Auth::id());

            $conds = [
                'payment_id' => Constants::offlinePaymentId,
                'attribute_key' => Constants::pmtAttrOfflinePaymentStatusCol,
                'core_keys_id' => $id
            ];
            $paymentAttribute = $this->paymentAttributeService->get(null, $conds);

            $paymentAttributeStatus = $this->prepareStatusData($paymentAttribute);

            $dataArr = $this->offlinePaymentSettingService->setStatus($paymentAttribute->id, $paymentAttributeStatus);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

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

    private function prepareIndexData($request)
    {
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'location_city_id' => $request->input('city_filter') === 'all' ? null : $request->input('city_filter'),
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'payment_id' => Constants::offlinePaymentId
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $dataWithRelation = ['core_key', 'statusAttribute'];
        $offlinePayments = OfflinePaymentSettingWithKeyResource::collection($this->paymentInfoService->getAll($dataWithRelation, null, null, $conds, false, $row));

        // prepare for permission
        $keyValueArr = [
            'createPayment' => 'create-payment',
        ];

        return [
            'offlinePayments' => $offlinePayments,
            'sort_field' => $conds['order_by'],
            'sort_order' => $request->sort_order,
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareEditData($id)
    {

        $dataWithRelation = ['core_key', 'offline_icon', 'statusAttribute'];
        $offlinePayment = $this->offlinePaymentSettingService->get($id, $dataWithRelation);
        return [
            'offlinePayment' => $offlinePayment,
        ];
    }

    private function prepareStatusData($paymentAttribute)
    {
        return $paymentAttribute->attribute_value == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }
}
