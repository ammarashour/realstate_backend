<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Configuration;

use App\Config\Cache\CacheBuilderAppInfoCache;
use App\Config\Cache\CheckVersionUpdateCache;
use App\Http\Controllers\PsController;
use App\Config\ps_constant;
use App\Http\Contracts\Configuration\BuilderSettingServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Http\Requests\Configuration\UpdateBuilderSettingRequest;
use Modules\Core\Http\Services\ApiCallSettingService;

class BuilderSettingController extends PsController
{
    private const parentPath = "builder_setting/";
    private const indexPath = self::parentPath . "Index";
    private const createPath = self::parentPath . "Create";
    private const editPath = self::parentPath . "Edit";
    private const indexRoute = "builder_setting.index";

    public function __construct(protected BuilderSettingServiceInterface $builderSettingService,
    protected ApiCallSettingService $apiCallSettingService)
    {
        parent::__construct();
    }


    public function index(Request $request)
    {
        $dataArr = $this->prepareIndexData();

        $dataArr['SettingPage'] = $request->query('page') ?? 0;
        
        return renderView(self::editPath, $dataArr);
    }

    public function create()
    {
        return view('core::create');
    }

    public function show($id)
    {
        return view('core::show');
    }

    public function edit($id)
    {
        return view('core::edit');
    }

    public function update(UpdateBuilderSettingRequest $request, $id)
    {
        try {

            $this->builderSettingService->update($id, $request);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function handleProjectReset()
    {
        $builder_setting = $this->builderSettingService->handleProjectReset();
        return redirect()->back()->with('status', $builder_setting);
    }

    public function checkVersionUpdate(Request $request) {
        
        $this->apiCallSettingService->update(Carbon::now()->subDay());
        PsCache::clear(CacheBuilderAppInfoCache::BASE);
        PsCache::clear(CheckVersionUpdateCache::BASE);

        $dataArr = [];
        $dataArr['SettingPage'] = $request->query('page') ?? 3;

        return redirectView(self::indexRoute, null, null, ["page=" . $dataArr['SettingPage']]);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {
        $project = $this->builderSettingService->get();
        $project['builder_url'] = ps_constant::builderDomain;

        return [
            'builder_setting' => $project
        ];
    }
}
