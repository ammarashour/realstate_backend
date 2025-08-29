<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\AppInfo;

use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\AppInfoService;
use Modules\Core\Transformers\Api\App\V1_0\Utilities\CoreFieldApiResource;

class AppInfoApiController extends Controller
{
    protected $appInfoService;

    public function __construct(AppInfoService $appInfoService, protected CoreFieldServiceInterface $coreFieldService)
    {
        $this->appInfoService = $appInfoService;
    }

    public function appInfo(Request $request)
    {
        $appInfo = $this->appInfoService->indexFromApi($request);
        return $appInfo;

    }
    public function feSettingConfig(Request $request)
    {
        $appInfo = $this->prepareFeSettingConfigData($request);
        if(isset($appInfo['core'])) {

            $core = CoreFieldApiResource::collection($appInfo['core']);
        }

        return response()->json([
            "core" => $core,
            "custom" => [],
            "vendor_list" => [],
        ],200);

    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareFeSettingConfigData($request)
    {
        $offset = $request->offset;
        $limit = $request->limit;
        $code = Constants::frontendSetting;


        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: $code, limit: $limit, offset: $offset, isDel: Constants::no, withNoPag: Constants::yes);

        $coreFields = Schema::getColumnListing('psx_frontend_settings');

        $core = [];

        foreach ($coreFields as $coreField) {
            foreach ($coreFieldFilterSettings as $coreFieldFilterSetting) {
                if (str_contains($coreFieldFilterSetting->field_name, "@@")) {
                    $originFieldName = strstr($coreFieldFilterSetting->field_name, "@@", true);
                } else {
                    $originFieldName = $coreFieldFilterSetting->field_name;
                }

                if ($coreField == $originFieldName) {
                    $coreFieldFilterSetting->placeholder = __($coreFieldFilterSetting->placeholder, [], $request->language_symbol);
                    $coreFieldFilterSetting->label_name = __($coreFieldFilterSetting->label_name, [], $request->language_symbol);

                    array_push($core, $coreFieldFilterSetting);
                }
            }
        }

        return [
            "core" => $core,
            "custom" => [],
            "vendor_list" => [],
        ];
    }

}
