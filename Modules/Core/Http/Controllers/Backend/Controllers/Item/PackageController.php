<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Requests\Item\StorePackageRequest;
use Modules\Core\Http\Services\Item\PackageService;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    const parentPath = "package/";
    const indexPath = self::parentPath."Index";
    const createPath = self::parentPath."Create";
    const editPath = self::parentPath."Edit";
    const indexRoute = "package.index";
    const createRoute = "package.create";
    const editRoute = "package.edit";

    protected $successFlag, $dangerFlag, $csvFile, $warningFlag;

    public function __construct(protected PackageService $packageService)
    {
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
    }

    public function index()
    {

        $dataArr = $this->packageService->index();
        return renderView(self::indexPath, $dataArr);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $dataArr = $this->packageService->create();
        return renderView(self::createPath, $dataArr);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StorePackageRequest $request)
    {

        $package = $this->packageService->store($request);

        // if have error
        if ($package){
            $msg = $package;
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $dataArr = $this->packageService->edit($id);
        return renderView(self::editPath, $dataArr);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $package = $this->packageService->update($id, $request);

        // if have error
        if ($package){
            $msg = $package;
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $dataArr = $this->packageService->destroy($id);
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }
}
