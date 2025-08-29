<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Vendor;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\Vendor;
use Modules\Core\Http\Services\Vendor\VendorService;
use Modules\Core\Transformers\Backend\Model\Vendor\VendorWithKeyResource;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use Modules\Core\Entities\Utilities\CustomField;

class VendorController extends PsController
{
    private const parentPath = 'vendor';

    private const indexPath = self::parentPath . '/Index';

    private const createPath = self::parentPath . '/Create';

    private const editPath = self::parentPath . '/Edit';

    private const indexRoute = 'vendor.index';

    public function __construct(
        protected VendorService $vendorService,
        protected CustomFieldServiceInterface $customizeUiService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected CustomFieldAttributeServiceInterface $customizeUiDetailService
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Vendor::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        // check permission
        $this->handlePermissionWithModel(Vendor::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function setSession()
    {
        $this->vendorService->setSession();

        return redirect()->route('vendor_info.index');
    }

    public function destroy($id)
    {
        try {
            $vendor = $this->vendorService->get($id);

            $this->handlePermissionWithModel($vendor, Constants::deleteAbility);

            $dataArr = $this->vendorService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function changeVendor($id)
    {
        $this->vendorService->setSession($id);

        return redirect()->route('vendor_info.index');
    }

    public function isUnlimitedChange($id)
    {
        try {
            $vendor = $this->vendorService->get($id);

            $isUnlimited = $this->prepareIsUnlimitedData($vendor);

            $this->vendorService->isUnlimitedChange($id, $isUnlimited);

            return redirectView(msg: "core__be_vendor_is_unlimited_updated");
        } catch (\Exception $e) {
            return redirectView(msg: $e->getMessage(), flag: Constants::danger);
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
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;
        $relation = ['owner', 'logo', 'banner_1', 'banner_2', 'vendorBranch'];

        $conds = [
            'page' => $request->input('page'),
            'order_by' => $request->input('sort_field'),
            'order_type' => $request->input('sort_order'),
            'searchterm' => $request->input('search') ?? '',
        ];

        $vendors = VendorWithKeyResource::collection($this->vendorService->getAll(null, Constants::vendorAcceptStatus, $relation, $row, $conds));

        $keyValueArr = [
            'createVendor' => 'create-vendor',
        ];

        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendor, $this->controlFieldArr());
        $showVendorCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter[ps_constant::handlingColumn];
        $columnFilterOptionProps = $columnAndColumnFilter[ps_constant::handlingFilter];

        return [
            'checkPermission' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
            'vendorList' => $vendors,
            'sort_field' => $conds['order_by'],
            'sort_order' => $request->input('sort_order'),
            'showVendorCols' => $showVendorCols,
            'showCoreAndCustomFieldArr' => $columnProps,
            'hideShowFieldForFilterArr' => $columnFilterOptionProps,
        ];
    }

    private function prepareShowData($id)
    {
        $relation = ['owner', 'logo', 'banner_1', 'banner_2', 'vendorBranch'];

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendor, withNoPag: 1);

        $branchesCoreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::vendorBranches, withNoPag: 1);

        $vendor = new VendorWithKeyResource($this->vendorService->get($id, $relation));

        $customizeUisByModule = $this->customizeUiService->getAll(moduleName: Constants::vendor, withNoPag: 1);

        $customizeDetails = $this->customizeUiDetailService->getAll(coreKeysId: $customizeUisByModule, noPagination: 1);

        return [
            'vendor' => $vendor,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'branchesCoreFieldFilterSettings' => $branchesCoreFieldFilterSettings,
            'customizeHeaders' => $customizeUisByModule,
            'customizeDetails' => $customizeDetails,
        ];
    }

    private function prepareIsUnlimitedData($vendor)
    {
        return $vendor->is_unlimited ? 0 : 1;
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
