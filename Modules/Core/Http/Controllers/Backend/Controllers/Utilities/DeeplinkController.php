<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Utilities;

use App\Config\ps_constant;
use App\Http\Contracts\Configuration\SettingServiceInterface;
use App\Http\Contracts\Item\ItemServiceInterface;
use App\Http\Contracts\Utilities\DynamicLinkServiceInterface;
use Modules\Core\Constants\Constants;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Services\Utilities\DeeplinkService;

class DeeplinkController extends Controller
{

    const parentPath = "deeplink_generator/";
    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "deeplink_generator.index";

    protected $deeplinkService, $dangerFlag;

    public function __construct(DeeplinkService $deeplinkService, protected SettingServiceInterface $settingService, protected ItemServiceInterface $itemService, protected DynamicLinkServiceInterface $dynamicLinkService)
    {
        $this->deeplinkService = $deeplinkService;
        $this->dangerFlag = Constants::danger;
    }

    public function index()
    {
        $dataArr = $this->deeplinkService->index();
        return renderView(self::editPath, $dataArr);
    }

    public function deeplink()
    {
        $dynamicLinkConfig = $this->settingService->get(env: ps_constant::DYNAMIC_LINK_CONFIG);
        $dynamicLinkSetting = json_decode($dynamicLinkConfig->setting, true);
        $linkProvider = $dynamicLinkSetting['default_dynamic_link']['id'];

        if ($linkProvider == ps_constant::FIREBASE) {
            $dataArr = $this->deeplinkService->deeplink();
        } elseif ($linkProvider == ps_constant::PSX_DYNAMIC_LINK) {
            $this->itemService->generateDynamicLinksForAllItems();
            $dataArr = [
                'msg' => __('core__be_deep_link'),
                'flag' => Constants::success
            ];
        }
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }
}
