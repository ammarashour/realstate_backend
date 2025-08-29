<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Information;

use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Information\CorePrivacyPolicy;
use App\Http\Contracts\Information\PrivacyPolicyServiceInterface;
use Modules\Core\Http\Requests\Information\StorePrivacyPolicyRequest;
use Modules\Core\Http\Requests\Information\UpdatePrivacyPolicyRequest;
use Illuminate\Http\Request;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Transformers\Backend\Model\Information\PrivacyPolicyWithKeyResource;
use App\Http\Controllers\PsController;

class PrivacyPolicyController extends PsController
{

    private const parentPath = "privacy_policy/";
    private const indexPath = self::parentPath . "Index";
    private const createPath = self::parentPath . "Create";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "privacy_policy.index";
    private const createRoute = "privacy_policy.create";
    private const editRoute = "privacy_policy.edit";

    public function __construct(protected PrivacyPolicyServiceInterface $privacyPolicyService, protected ImageService $imageService)
    {
        parent::__construct();
    }

    public function index()
    {
       // check permission
       $this->handlePermissionWithModel(CorePrivacyPolicy::class, Constants::viewAnyAbility);

       $dataArr = $this->prepareIndexData();

       return renderView(self::editPath, $dataArr);
    }

    public function save(StorePrivacyPolicyRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Privacy Policy
            $this->privacyPolicyService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function update(UpdatePrivacyPolicyRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Privacy Policy
            $this->privacyPolicyService->update($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }
    public function ckUpload(Request $request)
    {
        $imgParentId = 0;

        // save blog cover photo
        $url = $this->imageService->editorUpdateOrCreateImage($request, "upload", $imgParentId, 'privacy_policy');
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
        $privacy_policy = new PrivacyPolicyWithKeyResource($this->privacyPolicyService->get());

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::privacyPolicy, $this->controlFieldArr());

        $dataArr = [
            'privacy_policy' => $privacy_policy,
            'showPrivacyPolicyCols' => $columnAndColumnFilter[ps_constant::showCoreField],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
        ];
        return $dataArr;
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
