<?php
namespace Modules\Theme\Http\Services;


use App\Http\Services\PsService;
use Modules\Theme\Transformers\Api\App\V1_0\Theme\ThemeInfoForMobileApiResource;

class ThemeInfoService extends PsService
{
    public function getAllThemeInfoForMobileFromApi($request)
    {
        $getAllThemeInfoForMobile = new ThemeInfoForMobileApiResource($request);
        return $getAllThemeInfoForMobile;
    }
}
