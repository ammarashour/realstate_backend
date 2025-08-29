<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\CustomizeTheme;

use App\Config\ps_config;
use App\Config\ps_constant;
use App\Http\Contracts\CustomizeTheme\ThemePlatformServiceInterface;
use App\Http\Contracts\CustomizeTheme\ThemeScreenServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Backend\Model\ThemeScreen\ThemePlatformWithKeyResource;
use Modules\Core\Transformers\Backend\Model\ThemeScreen\ThemeScreenWithKeyResource;

class CustomizeThemeController extends PsController
{
    public function __construct(protected ThemeScreenServiceInterface $themeScreenService, protected ThemePlatformServiceInterface $themePlatformService) {}

    const parentPath = "customize_theme/";
    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "customize_theme.index";
    const createRoute = "customize_theme.create";
    const editRoute = "customize_theme.edit";

    public function index(Request $request)
    {
        $dataArr = $this->prepareIndexData($request);
        return renderView(self::indexPath, $dataArr);
    }

    public function statusChange(Request $request, $id)
    {
        try {
            $themeScreen = $this->themeScreenService->get($id);
            $status = $this->prepareStatusData($themeScreen);

            $this->themeScreenService->update($id, $status);
            $query = $this->getQuery($request);

            return redirectView(self::indexRoute, __('core__be_status_updated'), parameter: $query);
        } catch (\Exception $e) {
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
        $query = $this->getQuery($request);
        $themeScreenConds = [
            'columnSearch' => ['platform_id' => $query['platform_id']]
        ];
        $themeScreens = $this->themeScreenService->getAll(relation: ['theme_platforms'], pagPerPage: $query['row'], conds: $themeScreenConds);
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::themeScreen, $this->detailFieldArr());

        return [
            'query' => $query,
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'themeScreens' => ThemeScreenWithKeyResource::collection($themeScreens),
            'themePlatforms' => ThemePlatformWithKeyResource::collection($this->themePlatformService->getAll(noPagination: true))
        ];
    }

    private function prepareStatusData($themeScreen)
    {
        return ['is_publish' => !boolval($themeScreen->is_publish)];
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function getQuery(Request $request)
    {
        $themePlatformId = $request->input('platform_id') ?? ps_constant::webPlatformId;
        $themeScreenPagPerPage = $request->input('row') ?? ps_config::pagPerPage;
        return [
            'platform_id' => $themePlatformId,
            'row' => $themeScreenPagPerPage
        ];
    }

    private function detailFieldArr()
    {
        $detailFieldArr = [];
        $detailFieldObj = takingForColumnProps(__('core__be_detail'), 'core__be_detail', 'Action', false, 4);
        array_push($detailFieldArr, $detailFieldObj);

        return $detailFieldArr;
    }
}
