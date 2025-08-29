<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Configuration;

use Illuminate\Http\Request;
use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\PhoneCountryCode;
use App\Http\Contracts\Configuration\PhoneCountryCodeServiceInterface;
use Modules\Core\Http\Requests\Configuration\StorePhoneCountryCodeRequest;
use Modules\Core\Http\Requests\Configuration\UpdatePhoneCountryCodeRequest;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Transformers\Backend\Model\Configuration\PhoneCountryCodeWithKeyResource;

class PhoneCountryCodeController extends PsController
{
    private const parentPath = "phone_country_code/";
    private const indexPath = self::parentPath . "Index";
    private const createPath = self::parentPath . "Create";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "phone_country_code.index";
    private const createRoute = "phone_country_code.create";
    private const editRoute = "phone_country_code.edit";

    public function __construct(protected PhoneCountryCodeServiceInterface $phoneCountryCodeService, protected CoreFieldServiceInterface $coreFieldFilterSettingService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(PhoneCountryCode::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(PhoneCountryCode::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StorePhoneCountryCodeRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save phone country code
            $this->phoneCountryCodeService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        } 
    }

    public function show(PhoneCountryCode $phone_country_code)
    {
        return redirect()->route('phone_country_code.edit', $phone_country_code);
    }

    public function edit($id)
    {
        // check permission start
        $blog = $this->phoneCountryCodeService->get($id);
        $this->handlePermissionWithModel($blog, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdatePhoneCountryCodeRequest $request, $id)
    { 
        try {
            $validatedData = $request->validated();

            $this->phoneCountryCodeService->update($id, $validatedData);

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $phoneCountryCode = $this->phoneCountryCodeService->get($id);

            $this->handlePermissionWithModel($phoneCountryCode, Constants::deleteAbility);

            $dataArr = $this->phoneCountryCodeService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $phoneCountryCode = $this->phoneCountryCodeService->get($id);

            $this->handlePermissionWithModel($phoneCountryCode, Constants::editAbility);

            $status = $this->prepareStatusData($phoneCountryCode);

            $this->phoneCountryCodeService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function defaultChange($id)
    {
        try {

            $phoneCountryCode = $this->phoneCountryCodeService->get($id);

            $this->handlePermissionWithModel($phoneCountryCode, Constants::editAbility);
            if($phoneCountryCode->is_default == Constants::unPublish)
            {
                $status = $this->prepareIsDefaultData($phoneCountryCode);
                
                $this->phoneCountryCodeService->defaultChange($id, $status);
            }

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

    private function prepareIndexData($request)
    {
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $phoneCountryCodes = PhoneCountryCodeWithKeyResource::collection($this->phoneCountryCodeService->getAll(null, null, null, null, false,  $row, $conds));

         // taking for column and columnFilterOption
         $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::phoneCountryCode, $this->controlFieldArr());

         // prepare for permission
        $keyValueArr = [
            'createPhoneCountryCode' => 'create-phoneCountryCode',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'phoneCountryCodes' => $phoneCountryCodes,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareCreateData()
    {
        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getAll(withNoPag: 1, code: Constants::phoneCountryCode);

        return [
            "coreFieldFilterSettings" => $coreFieldFilterSettings
        ];
    }

    private function prepareEditData($id)
    {
        $phoneCountryCode = $this->phoneCountryCodeService->get($id);

        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getAll(withNoPag: 1, code: Constants::phoneCountryCode);

        return [
            "phone_country_code" => $phoneCountryCode,
            "coreFieldFilterSettings" => $coreFieldFilterSettings
        ];
    }

    private function prepareStatusData($phoneCountryCode)
    {
        return $phoneCountryCode->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareIsDefaultData($phoneCountryCode)
    {
        return $phoneCountryCode->is_default == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), 'action', 'Action', false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        return $controlFieldArr;
    }


}
