<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Configuration;

use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\MobileSetting;
use Modules\Core\Http\Requests\Configuration\StoreMobileSettingRequest;
use Modules\Core\Http\Requests\Configuration\UpdateMobileSettingRequest;
use App\Http\Contracts\Configuration\ColorServiceInterface;
use App\Http\Contracts\Localization\MobileLanguageServiceInterface;
use Illuminate\Http\Request;
use Modules\Core\Transformers\Backend\Model\Configuration\MobileSettingWithKeyResource;

class MobileSettingController extends PsController
{
    private const parentPath = 'mobile_setting';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.edit';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(protected MobileSettingServiceInterface $mobileSettingService,
        protected MobileLanguageServiceInterface $mobileLanguageService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected ColorServiceInterface $colorService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission start
        $this->handlePermissionWithModel(MobileSetting::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData();

        $dataArr['SettingPage'] = $request->query('page') ?? 0;

        return renderView(self::editPath, $dataArr);
    }

    public function store(StoreMobileSettingRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $this->mobileSettingService->save(mobileSettingData: $validatedData,
                mobileColors: json_decode($request->input('mobileColors')));

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function update(UpdateMobileSettingRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->mobileSettingService->update(id: $id,
                mobileSettingData: $validatedData,
                mobileColors: json_decode($request->input('mobileColors')));

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage(), $id);
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
        $mobileSetting = new MobileSettingWithKeyResource($this->mobileSettingService->get(id: null));

        $availableLanguages = $this->mobileLanguageService->getAll(enable: Constants::enable, noPagination: Constants::yes);

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::mobileSetting,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        $mbColorConds = $this->getMobileColorConds();
        $mobileColors = $this->colorService->getAll(null, null, $mbColorConds);

        $keyValueArr = [
            'updateMobileSetting' => 'update-mobileSetting',
        ];

        return [
            'mobile_setting' => $mobileSetting,
            'available_languages' => $availableLanguages,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'mobileColors' => $mobileColors,
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    //----------------------------------------------------------------
    // Others
    //----------------------------------------------------------------
    private function getMobileColorConds()
    {
        return [
            'mb_color' => Constants::yes,
        ];
    }
}
