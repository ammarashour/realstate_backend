<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Vendor;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsController;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Services\Vendor\VendorService;
use Modules\Core\Transformers\Backend\Model\Vendor\VendorWithKeyResource;
use App\Http\Contracts\Vendor\VendorApprovalServiceInterface;
use App\Http\Contracts\Vendor\VendorApplicationServiceInterface;

class VendorApprovalController extends PsController
{
    private const parentPath = "vendor_approval/";
    private const indexPath = self::parentPath . 'Index';
    private const createPath = self::parentPath . 'Create';
    private const editPath = self::parentPath . 'Edit';
    private const indexRoute = "pending_vendor.index";


    public function __construct(
        protected VendorApprovalServiceInterface $vendorApprovalService,
        protected VendorService $vendorService,
        protected VendorApplicationServiceInterface $vendorApplicationService,
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->handlePermissionWithoutModel(Constants::pendingVendorModule, ps_constant::readPermission, Auth::user()->id);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        $this->handlePermissionWithoutModel(Constants::pendingVendorModule, ps_constant::readPermission, Auth::user()->id);

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function destroy($id)
    {
        try {
            $this->handlePermissionWithoutModel(Constants::pendingVendorModule, ps_constant::deletePermission, Auth::user()->id);

            $dataArr = $this->vendorApprovalService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange(Request $request, $id)
    {
        try {
            $this->handlePermissionWithoutModel(Constants::pendingVendorModule, ps_constant::updatePermission, Auth::user()->id);
            
            $dataArr = $this->vendorApprovalService->setStatus($id, $request->status);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function downloadDocument($id)
    {
        $dataArr = $this->vendorApplicationService->downloadDocument(null, $id);

        return redirectView(self::indexRoute, $dataArr);
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
            'added_date_ranger' => $request->input('added_date_filter') == "all" ? null : $request->added_date_filter,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;
        $relation = ['logo', 'banner_1', 'banner_2', 'vendorBranch'];

        $applications = $this->vendorService->getAll(null, Constants::vendorPendingStatus, $relation, $row, $conds);
        $vendorApplications = VendorWithKeyResource::collection($applications);
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::vendor, $this->controlFieldArr());
        $showVendorCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter[ps_constant::handlingColumn];
        $columnFilterOptionProps = $columnAndColumnFilter[ps_constant::handlingFilter];
        // prepare for permission
        $keyValueArr = [
            'createVendor' => 'create-vendor',
        ];

        if ($conds['order_by']) {
            $dataArr = [
                'checkPermission' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
                'vendorApplications' => $vendorApplications,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->input('sort_order'),
                'showVendorCols' => $showVendorCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
            ];
        } else {
            $dataArr = [
                'checkPermission' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
                'vendorApplications' => $vendorApplications,
                'showVendorCols' => $showVendorCols,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
            ];
        }

        return $dataArr;
    }

    private function prepareShowData($id)
    {

        $relation = ['owner', 'vendor_application'];
        $vendor = new VendorWithKeyResource($this->vendorService->get($id, $relation));
        $application = $this->vendorApplicationService->get($vendor->vendor_application->id);

        $dataArr = [
            'vendor' => $vendor,
            'application' => $application,
        ];
        return $dataArr;
    }

    private function prepareStatusData($vendor)
    {
        return $vendor->status == "0" || "2"
            ? 'accept'
            : 'reject';
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
