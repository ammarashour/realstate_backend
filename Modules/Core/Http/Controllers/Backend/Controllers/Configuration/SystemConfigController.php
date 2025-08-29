<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Configuration;

use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Configuration\SystemConfigServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\AdPostType;
use Modules\Core\Entities\Configuration\SystemConfig;
use Modules\Core\Http\Requests\Configuration\UpdateSystemConfigRequest;
use Modules\Core\Transformers\Backend\Model\Configuration\MobileSettingWithKeyResource;
use Modules\Core\Transformers\Backend\Model\Configuration\SystemConfigWithKeyResource;

class SystemConfigController extends PsController
{
    private const parentPath = 'system_config';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const createPath1 = self::parentPath.'/Create1';

    private const createPath2 = self::parentPath.'/Create2';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const adsTxtFileKey = 'ads_txt_file';

    public function __construct(protected SystemConfigServiceInterface $systemConfigService,
        protected MobileSettingServiceInterface $mobileSettingService,
        protected CoreFieldServiceInterface $coreFieldService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission start
        $this->handlePermissionWithModel(SystemConfig::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData();

        $dataArr['SettingPage'] = $request->query('page') ?? 0;

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateSystemConfigRequest $request)
    {
        try {
            $validateData = $request->validated();

            $adsTxtFile = $request->file('sysForm.'.self::adsTxtFileKey);

            $this->systemConfigService->update(id: null,
                systemConfigData: $validateData['sysForm'],
                adsTxtFile: $adsTxtFile,
                mobileSettingId: null,
                mobileSettingData: $validateData['form']
            );

            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectView(self::indexRoute, $e->getMessage());
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
        $systemConfig = new SystemConfigWithKeyResource($this->systemConfigService->get(id: null, relation: null));

        $mobileSetting = new MobileSettingWithKeyResource($this->mobileSettingService->get(id: null, relation: null));

        $adTypes = AdPostType::all();

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::systemConfig,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        $keyValueArr = [
            'updateFrontendSetting' => 'update-frontendSetting',
        ];

        return [
            'system_config' => $systemConfig,
            'mobile_setting' => $mobileSetting,
            'adTypes' => $adTypes,
            'mbCoreFieldFilterSettings' => $coreFieldFilterSettings,
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }
}
