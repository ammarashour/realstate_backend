<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\DownloadDB;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\DownloadDBService;

class DownloadDBController extends PsController
{
    const parentPath = "download_db/";
    const indexPath = self::parentPath . "Index";
    const indexRoute = "download_db.index";

    protected $downloadDBService;

    public function __construct(DownloadDBService $downloadDBService)
    {
        parent::__construct();

        $this->downloadDBService = $downloadDBService;
    }

    public function index()
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::downloadDbModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->downloadDBService->index();
        return renderView(self::indexPath, $dataArr);
    }

    public function downloadDB()
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::downloadDbModule, ps_constant::deletePermission, Auth::id());
        
        Artisan::call("backup:run --only-db -q");
        $files = File::files(public_path(env("APP_NAME")));

        if (!empty($files)) {
            // Sort the files by last modified time in descending order
            usort($files, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });

            // Get the latest file
            $latestFile = $files[0]->getPathname();
            return response()->download($latestFile)->deleteFileAfterSend(true);
        }

    }
}
