<?php

namespace Modules\Theme\Http\Controllers\Backend\Rests\App\V1_0\Theme;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Theme\Http\Services\ThemeInfoService;

class ThemeInfoApiController extends Controller
{
    protected $themeInfoService, $badRequestStatusCode;

    public function __construct(ThemeInfoService $themeInfoService)
    {
        $this->themeInfoService = $themeInfoService;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
    }

    public function getAllThemeInfoForMobile(Request $request)
    {
        $data = $this->themeInfoService->getAllThemeInfoForMobileFromApi($request);
        return $data;
    }

}
