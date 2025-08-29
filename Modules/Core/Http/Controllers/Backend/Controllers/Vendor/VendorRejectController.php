<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Vendor;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsController;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Services\Vendor\VendorService;
use Modules\Core\Transformers\Backend\NoModel\Vendor\VendorRejectWithKeyResource;
use App\Http\Contracts\Vendor\VendorRejectServiceInterface;
use App\Http\Contracts\Vendor\VendorApplicationServiceInterface;
use Modules\Core\Transformers\Backend\Model\Vendor\VendorWithKeyResource;

class VendorRejectController extends PsController
{
    private const parentPath = "vendor_reject";
    private const indexPath = self::parentPath . '/Index';
    private const createPath = self::parentPath . '/Create';
    private const editPath = self::parentPath . '/Edit';
    private const indexRoute = "reject_vendor.index";


    public function __construct(protected VendorRejectServiceInterface $vendorRejectService,
    protected VendorService $vendorService,
    protected VendorApplicationServiceInterface $vendorApplicationService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->handlePermissionWithoutModel(Constants::rejectVendorModule, ps_constant::readPermission, Auth::user()->id);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        $this->handlePermissionWithoutModel(Constants::rejectVendorModule, ps_constant::readPermission, Auth::user()->id);

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function destroy($id)
    {
        try
        {
            $this->handlePermissionWithoutModel(Constants::rejectVendorModule, ps_constant::deletePermission, Auth::user()->id);
    
            $dataArr = $this->vendorRejectService->delete($id);
    
            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        }catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try
        {
            $this->handlePermissionWithoutModel(Constants::pendingVendorModule, ps_constant::updatePermission, Auth::user()->id);
    
            $vendor = $this->vendorService->get($id);

            $status = $this->prepareStatusData($vendor);
           
            $dataArr = $this->vendorRejectService->setStatus($id, $status);
    
            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        }catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function downloadDocument($id)
    {
        $dataArr = $this->vendorApplicationService->downloadDocument(null, $id);

        return $dataArr;
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

        $applications = $this->vendorService->getAll(null, Constants::vendorRejectStatus, $relation, $row, $conds);
        $vendorApplications = VendorRejectWithKeyResource::collection($applications);

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
        return $vendor->status == "1"
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
