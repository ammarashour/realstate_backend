<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Information;

use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Information\CoreDataDeletion;
use App\Http\Contracts\Information\DataDeletionPolicyServiceInterface;
use Modules\Core\Http\Requests\Information\StoreDataDeletionPolicyRequest;
use Modules\Core\Http\Requests\Information\UpdateDataDeletionPolicyRequest;
use Illuminate\Http\Request;
use Modules\Core\Http\Services\ImageService;
use App\Http\Controllers\PsController;
use Modules\Core\Transformers\Backend\Model\Information\DataDeletionPolicyWithKeyResource;

class DataDeletionPolicyController extends PsController
{
    private const parentPath = "data_deletion_policy/";
    private const indexPath = self::parentPath . "Index";
    private const createPath = self::parentPath . "Create";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "data_deletion_policy.index";
    private const createRoute = "data_deletion_policy.create";
    private const editRoute = "data_deletion_policy.edit";

    public function __construct(protected DataDeletionPolicyServiceInterface $dataDeletionService,
    protected ImageService $imageService)
    {
        parent::__construct();
    }

    public function index()
    {
        // check permission
        $this->handlePermissionWithModel(CoreDataDeletion::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData();

        return renderView(self::editPath, $dataArr);
    }


    public function store(StoreDataDeletionPolicyRequest $request)
    {

        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Data Deletion Policy
            $this->dataDeletionService->save($validData);

            // Success and Redirect
            return redirectView(self::editPath);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editPath, $e->getMessage());
        }
    }

    public function update(UpdateDataDeletionPolicyRequest $request,$id)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Data Deletion Policy
            $this->dataDeletionService->update($id, $validData);

            // Success and Redirect
            return redirectView(self::editPath);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editPath, $e->getMessage());
        }
    }
    public function ckUpload(Request $request)
    {

        $imgParentId = 0 ;

        // save blog cover photo
        $url=$this->imageService->editorUpdateOrCreateImage($request, "upload", $imgParentId, 'data_deletetion_policy');
        return response()->json([
            'uploaded' => true,
            'url' => $url,
        ]);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {

        $data_deletion_policy = new DataDeletionPolicyWithKeyResource($this->dataDeletionService->get());

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::dataDeletion, $this->controlFieldArr());

       return [
            'data_deletion_policy' => $data_deletion_policy,
            'showDataDeletionCols' => $columnAndColumnFilter[ps_constant::showCoreField],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
        ];
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
