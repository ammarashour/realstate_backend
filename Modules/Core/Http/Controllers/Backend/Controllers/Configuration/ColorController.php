<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Configuration;

use Illuminate\Http\Request;
use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\Color;;
use Modules\Core\Http\Requests\Configuration\StoreColorRequest;
use Modules\Core\Http\Requests\Configuration\UpdateColorRequest;
use App\Http\Contracts\Configuration\ColorServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Transformers\Backend\Model\Configuration\ColorWithKeyResource;

class ColorController extends PsController
{
    private const parentPath = "color";
    private const indexPath = self::parentPath . "/Index";
    private const createPath = self::parentPath . "/Create";
    private const editPath = self::parentPath . "/Edit";
    private const indexRoute = "color.index";
    private const createRoute = "color.create";
    private const editRoute = "color.edit";

    public function __construct(
        protected ColorServiceInterface $colorService,
        protected CoreFieldServiceInterface $coreFieldFilterSettingService
    ) {
        parent::__construct();
    }



    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Color::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(Color::class, Constants::createAbility);

        return renderView(self::createPath);
    }

    public function store(StoreColorRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Color
            $this->colorService->save($validData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $color = $this->colorService->get($id);
        $this->handlePermissionWithModel($color, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateColorRequest $request, $id)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save Color
            $this->colorService->update($id, $validData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $blog = $this->colorService->get($id);

            $this->handlePermissionWithModel($blog, Constants::deleteAbility);

            $dataArr = $this->colorService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
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

    private function prepareIndexData($request)
    {
        $color = new ColorWithKeyResource($this->colorService->getAll());

        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getAll(Constants::color);

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::color, $this->controlFieldArr());

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'showAboutCols' => $columnAndColumnFilter[ps_constant::showCoreField],
            'color' => $color,
            'coreFieldFilterSettings' => $coreFieldFilterSettings
        ];
    }

    private function prepareEditData($id)
    {
        $color = $this->colorService->get($id);

        return [
            "color" => $color
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

