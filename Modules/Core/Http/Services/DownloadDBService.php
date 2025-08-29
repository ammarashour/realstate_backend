<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\MenuGroupService;

class DownloadDBService extends PsService
{
    protected $menuGroupService, $superAdminRoleId, $normalUserRoleId, $imageService;

    protected $upload_path = 'storage/uploads/';
    protected $thumb1x_path = 'storage/thumbnail/';
    protected $thumb2x_path = 'storage/thumbnail2x/';
    protected $thumb3x_path = 'storage/thumbnail3x/';

    public function __construct(MenuGroupService $menuGroupService, ImageService $imageService)
    {
        $this->menuGroupService = $menuGroupService;
        $this->imageService = $imageService;

        $this->superAdminRoleId= Constants::superAdminRoleId;
        $this->normalUserRoleId = Constants::normalUserRoleId;
        $this->superAdminRoleId = Constants::superAdminRoleId;
        $this->normalUserRoleId = Constants::normalUserRoleId;
    }

    public function index()
    {
        $relation = ['sub_menu_group'];
        $menus = $this->menuGroupService->getMenuGroups($relation);
        $dataArr = [
            "menus" => $menus,
        ];
        return $dataArr;
    }

}
