<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Config\ps_constant;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Configuration\SettingServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\ItemService;

class DeeplinkService extends PsService
{
    protected $successFlag, $dangerFlag, $itemService, $imageService;

    public function __construct(protected BackendSettingServiceInterface $backendSettingService, ItemService $itemService, ImageService $imageService, protected SettingServiceInterface $settingService)
    {
        $this->itemService = $itemService;
        $this->imageService = $imageService;
        $this->dangerFlag = Constants::danger;
        $this->successFlag = Constants::success;
    }

    public function index(){
        $backendSetting = $this->backendSettingService->get();
        $dynamicLinkSetting = $this->settingService->get(env: ps_constant::DYNAMIC_LINK_CONFIG);
        $dataArr = [
            'backend_setting' => $backendSetting,
            'dynamic_link_setting' => $dynamicLinkSetting
        ];
        return $dataArr;
    }

    public function deeplink(){

        try {
            $items = Item::all();
            foreach($items as $item){
                $conds = ['img_parent_id' => $item->id, 'img_type' => 'item', 'ordering' => 1];
                $img = ($this->imageService->getImage($conds))? $this->imageService->getImage($conds)->img_path:'';
                $dynamic_link = deeplinkGenerate($item->id, $item->title, $item->description, $img);
                if($dynamic_link['flag'] == 'error')
                {
                    $status = [
                        'msg' =>$dynamic_link['msg'],
                        'flag' => $this->dangerFlag,
                    ];
                    return $status;
                }
                $item->dynamic_link = $dynamic_link['msg'];
                $item->update();
            }
            $status = [
                'msg' => __('core__be_deep_link'),
                'flag' => $this->successFlag,
            ];
            return $status;

        } catch (\Throwable $e){
            $msg = $e->getMessage();
            $status = [
                'msg' => $msg,
                'flag' => $this->dangerFlag,
            ];
            return $status;
        }

    }







}
