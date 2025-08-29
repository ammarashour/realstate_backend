<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Table;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\CoreKeyType;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Localization\LanguageString;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Table;
use Modules\Core\Http\Services\ProjectService;
use Modules\Core\Http\Services\TableService;

class TableController extends Controller
{

    const parentPath = "table/";
    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "table.index";
    const createRoute = "table.create";
    const editRoute = "table.edit";

    protected $projectService, $tableService, $successFlag, $dangerFlag, $csvFile, $warningFlag;

    public function __construct(ProjectService $projectService, TableService $tableService)
    {
        $this->tableService = $tableService;
        $this->projectService = $projectService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->warningFlag = Constants::warning;
        $this->csvFile = Constants::csvFile;
    }

    public function index(Request $request)
    {
        if (empty($this->projectService->getProject()->ps_license_code)) {
            return redirect()->route('admin.index');
        }

        $dataArr = $this->tableService->index($request);

        if (!empty($checkPermission)) {
            return $checkPermission;
        }

        return renderView(self::indexPath, $dataArr);
    }

    /**
     * @deprecated
     */
    public function import(Request $request)
    {
        $dataArr = $this->tableService->import($request);
        if ($request->selectedFileName || $request->zipFileName) {
            return redirect()->route('table.index')->with($dataArr);
        }
        return redirect()->back()->with($dataArr);
        // return redirectView(self::indexRoute, $dataArr);
    }

    /**
     * @deprecated
     */
    public function overrideFieldsAndLangs(Request $request)
    {
        $dataArr = $this->tableService->overrideFieldsAndLangs($request);
        //        return $dataArr;
        // return redirectView(self::indexRoute, $dataArr);
        return redirect()->back()->with($dataArr);
    }

    /**
     * @deprecated
     */
    public function handleProjectNotSame(Request $request)
    {
        $dataArr = $this->tableService->handleProjectNotSame($request);
        // return $dataArr;
        // return redirectView(self::indexRoute, $dataArr);
        return redirect()->back()->with($dataArr);
    }

    /**
     * @deprecated
     */
    public function replaceFromBuilder()
    {
        $dataArr = [
            "builderApiOverwrite" => true,
        ];
        return redirect()->route('table.index')->with($dataArr);
    }

    /**
     * @deprecated
     */
    public function doReplaceFromBuilder()
    {
        $dataArr = $this->tableService->doReplaceFromBuilder();

        return redirect()->route('table.index')->with($dataArr);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('core::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
