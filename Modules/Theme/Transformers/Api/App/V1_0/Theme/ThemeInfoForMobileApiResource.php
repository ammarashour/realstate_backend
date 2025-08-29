<?php

namespace Modules\Theme\Transformers\Api\App\V1_0\Theme;

use App\Config\ps_constant;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Facades\MobileSettingFacade;
use Modules\Theme\Entities\ComponentAttribute;
use Modules\Theme\Entities\SelectedTheme;
use stdClass;

class ThemeInfoForMobileApiResource extends JsonResource
{
    public function toArray($request)
    {
        $mobileSetting = MobileSettingFacade::get();
        $selectedTheme = SelectedTheme::first();
        $selectedThemeId = $selectedTheme?->id;

        $componentAttributes = ComponentAttribute::where("platform_id", ps_constant::mobilePlatformId)->get();

        $mobileScreens = $componentAttributes->pluck("screen_id")->unique();

        $screenArr = [];
        $componentArr = [];


        foreach($mobileScreens as $mobileScreen){
            $screenObj = new stdClass();
            $screenObj->id = $mobileScreen;
            $thisScreenOfComponents = ComponentAttribute::where("screen_id", $mobileScreen)->get();
            foreach($thisScreenOfComponents as $thisScreenOfComponent){
                $componentObj = new stdClass();
                $componentObj->id = (string)$thisScreenOfComponent->component_id;
                $componentObj->attribute = json_decode($thisScreenOfComponent->attributes);
                array_push($componentArr, $componentObj);
            }
            $screenObj->components = $componentArr;
            array_push($screenArr, $screenObj);

            $componentArr = [];
        }


        $themeInfoObj = new stdClass();
        $themeInfoObj->theme_id = (string)$selectedThemeId;
        $themeInfoObj->theme_name = (string)$selectedTheme?->theme_name;
        $themeInfoObj->screens = $screenArr;
        // $themeInfoObj->theme_component_attr_change_code = isset($mobileSetting->theme_component_attr_change_code) ? (string) $mobileSetting->theme_component_attr_change_code : '';

        return [
            "theme_info" => $themeInfoObj,
        ];
    }
}
