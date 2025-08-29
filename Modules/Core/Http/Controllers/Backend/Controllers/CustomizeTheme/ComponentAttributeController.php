<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\CustomizeTheme;

use App\Config\ps_config;
use App\Config\ps_constant;
use App\Http\Contracts\CustomizeTheme\ComponentAttributeServiceInterface;
use App\Http\Contracts\CustomizeTheme\ThemePlatformServiceInterface;
use App\Http\Contracts\CustomizeTheme\ThemeScreenServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Transformers\Backend\Model\ThemeScreen\ComponentAttributeWithKeyResource;
use Modules\Core\Transformers\Backend\Model\ThemeScreen\ThemePlatformWithKeyResource;
use Modules\Core\Transformers\Backend\Model\ThemeScreen\ThemeScreenWithKeyResource;

class ComponentAttributeController extends PsController
{
    const parentPath = "component_attribute/";
    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "component_attribute.index";
    const createRoute = "component_attribute.create";
    const editRoute = "component_attribute.edit";

    public function __construct(protected ComponentAttributeServiceInterface $componentAttributeService, protected ThemeScreenServiceInterface $themeScreenService, protected ThemePlatformServiceInterface $themePlatformService) {}

    public function index(Request $request)
    {
        $dataArr = $this->prepareIndexData($request);
        return renderView(self::indexPath, $dataArr);
    }

    public function visibilityChange(Request $request, $id)
    {
        try {
            $componentAttribute = $this->componentAttributeService->get($id);
            $attributeData = $this->prepareAttributeData($componentAttribute);
            $this->componentAttributeService->update($id, $attributeData);

            return redirectView(self::indexRoute, __('core__be_status_updated'), parameter: ['screen_id' => $componentAttribute->screen_id, 'platform_id' => $componentAttribute->platform_id]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------
    private function prepareIndexData(Request $request)
    {
        $themeScreen = $this->themeScreenService->get(conds: ['columnSearch' => ['id' => $request->get('screen_id')]]);
        $componentAttributes = $this->componentAttributeService->getAll(noPagination: true, platformId: $request->get('platform_id'), conds: ['columnSearch' => ['screen_id' => $request->get('screen_id')]]);
        $themePlatform = $this->themePlatformService->get($request->get('platform_id'));

        if (!$themeScreen || !$componentAttributes) {
            abort(404);
        }
        return [
            'query' => $this->getQuery($request),
            'themePlatform' => new ThemePlatformWithKeyResource($themePlatform),
            'componentAttributes' => ComponentAttributeWithKeyResource::collection($componentAttributes),
            'themeScreen' => new ThemeScreenWithKeyResource($themeScreen)
        ];
    }

    private function prepareAttributeData($componentAttribute)
    {
        $attributes = json_decode($componentAttribute->attributes, true) ?? [];
        $attributes['is_show'] = ($attributes['is_show'] ?? 0) ? "0" : "1";
        return ['attributes' => json_encode($attributes)];
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function getQuery(Request $request)
    {
        $platformId = $request->input('platform_id', ps_constant::webPlatformId);
        $screenId = $request->input('screen_id', null);
        return [
            'platform_id' => $platformId,
            'screen_id' => $screenId
        ];
    }
}
