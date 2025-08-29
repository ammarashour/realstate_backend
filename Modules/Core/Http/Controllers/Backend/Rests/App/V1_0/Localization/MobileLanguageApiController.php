<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Localization;

use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use Illuminate\Http\Request;
use App\Http\Contracts\Localization\MobileLanguageServiceInterface;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Localization\MobileLanguageApiResource;
use App\Http\Controllers\PsApiController;
use Modules\Core\Entities\Configuration\BackendSetting;

class MobileLanguageApiController extends PsApiController
{

    public function __construct(
        protected MobileLanguageServiceInterface $mobileLanguageService,
        protected MobileSettingServiceInterface $mobileSettingService
    ) {
        parent::__construct();
    }

    public function langs(Request $request)
    {
        $backendSetting = BackendSetting::first();
        dd("hi");
        // return 'hi';
        $import = file_get_contents(base_path('lang/' . $request->symbol . '.json'));

        $fe_import = file_get_contents(base_path('/Modules/Template/PSXFETemplate/Resources/frontend_languages/' . $request->symbol . '.json'));


        // echo json_encode($import);exit;
        if (!$import) {
            $import = file_get_contents(base_path('lang/en.json'));
        }
        if (!$fe_import) {
            $fe_import = file_get_contents(base_path('/Modules/Template/PSXFETemplate/Resources/frontend_languages/en.json'));
        }


        $mergedObject = json_encode(array_merge(json_decode($import, true), json_decode($fe_import, true)));

        if (!empty($backendSetting->vendor_setting)) {
            $vendor_import = file_get_contents(base_path('/Modules/StoreFront/VendorPanel/Resources/vendor_languages/' . $request->symbol . '.json'));

            if (!$vendor_import) {
                $vendor_import = file_get_contents(base_path('/Modules/StoreFront/VendorPanel/Resources/vendor_languages/en.json'));
            }

            $mergedObject = json_encode(array_merge(json_decode($mergedObject, true), json_decode($vendor_import, true)));
        }



        return $mergedObject;
    }

    public function index(Request $request)
    {
        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $mobileLanguages = $this->mobileLanguageService->getAll(Constants::enable, $limit, $offset);

        $data = MobileLanguageApiResource::collection($mobileLanguages);

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);
    }

    public function search(Request $request)
    {
        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        $mobileLanguages = $this->mobileLanguageService->getAll(Constants::enable, $limit, $offset, $conds);

        $data = MobileLanguageApiResource::collection($mobileLanguages);

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
    }

    private function getFilterConditions($request)
    {
        return [
            'searchterm' => $request->keyword,
            'order_by' => $request->order_by,
            'order_type' => $request->order_type,
        ];
    }
}
