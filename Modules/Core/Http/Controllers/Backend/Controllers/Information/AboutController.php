<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Information;

use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\Information\StoreAboutRequest;
use Modules\Core\Http\Requests\Information\UpdateAboutRequest;
use App\Http\Controllers\PsController;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use Modules\Core\Entities\CoreImage;
use App\Http\Contracts\Image\ImageServiceInterface;
use Modules\Core\Entities\CoreAbout;
use Modules\Core\Transformers\Backend\Model\Information\AboutWithKeyResource;
use App\Http\Contracts\Information\AboutServiceInterface;

class AboutController extends PsController
{
    private const parentPath = "about";
    private const indexPath = self::parentPath."/Index";
    private const createPath = self::parentPath."/Create";
    private const editPath = self::parentPath."/Edit";
    private const indexRoute = "about.index";
    private const createRoute = "about.create";
    private const editRoute = "about.edit";

    public function __construct(protected AboutServiceInterface $aboutService,
    protected CoreFieldServiceInterface $coreFieldFilterSettingService,
    protected ImageServiceInterface $imageService)
    {
        parent::__construct();
    }

    public function index()
    {
        // check permission
        $this->handlePermissionWithModel(CoreAbout::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData();

        return renderView(self::editPath, $dataArr);
    }

    public function store(StoreAboutRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Save About
            $this->aboutService->save($validData);

            // Success and Redirect
            return redirectView(self::editRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function update(UpdateAboutRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            // Get Image File
            $file = $request->file('about_cover');

            $this->aboutService->update($id, $validatedData, $file);

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {
        $coreFieldFilterSettings = $this->coreFieldFilterSettingService->getAll(code: Constants::about, withNoPag: 1);

        $about = new AboutWithKeyResource($this->aboutService->get());

        $conds[CoreImage::imgType] = "about";
        $image = $this->imageService->get($conds);

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::about, $this->controlFieldArr());
        $conds = [
            'module_name' => Constants::about,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = $this->coreFieldFilterSettingService->getAll(conds: $conds);

        $validation = [];

        foreach ($core_headers as $core_header) {
            if ($core_header->field_name == 'about_cover') {
                array_push($validation, 'about_cover');
            }
        }
        return [
            'validation' => $validation,
            'about' => $about,
            'image' => $image,
            'showAboutCols' => $columnAndColumnFilter[ps_constant::showCoreField],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'coreFieldFilterSettings' => $coreFieldFilterSettings
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
