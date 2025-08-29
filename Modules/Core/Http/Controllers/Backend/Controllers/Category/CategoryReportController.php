<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Category;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Shop;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Location\LocationCity;
use Illuminate\Contracts\Support\Renderable;
use Modules\Core\Exports\CategoryReportExport;
use Modules\Core\Http\Services\Category\CategoryService;
use App\Config\ps_constant;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PsController;
use Carbon\Carbon;
use Modules\Core\Transformers\Backend\NoModel\CategoryReport\CategoryReportWithKeyResource;

class CategoryReportController extends PsController
{

    const parentPath = "category_report/";
    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "category.index";
    const createRoute = "category.create";
    const editRoute = "category.edit";

    protected $csvFileName, $categoryService, $successFlag, $dangerFlag, $csvFile;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->csvFile = Constants::csvFile;
        $this->csvFileName = "category_report";
    }

    // Category Report
    public function categoryReportIndex(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::categoryReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareCategoryReportIndexData($request);
        return renderView(self::indexPath, $dataArr);
    }

    public function categoryReportShow($id)
    {
        $dataArr = $this->prepareCategoryReportShowData($id);
        return renderView(self::editPath, $dataArr);
    }

    public function categoryReportCsvExport()
    {
        // filename
        return $this->prepareCategoryReportCsvExportData();
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareCategoryReportIndexData($request)
    {

        // search filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['order_by'] = 'category_touch_count';
        $conds['order_type'] = 'desc';
        // $conds['selected_date'] = $request->input('date_filter') == 'all'? null  : $request->date_filter;

        $date_range = null;
        if (!empty($request->date_filter) && $request->date_filter != 'all') {
            $start_date = $request->date_filter[0];
            $end_date = $request->date_filter[1];
            if (empty($end_date)) {
                $end_date = Carbon::now();
            }
            $date_range = [$start_date, $end_date];
        }

        $conds['selected_date'] = $request->input('date_filter') == 'all' ? null  : $date_range;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $relation = ['cover', 'icon'];
        $categories = CategoryReportWithKeyResource::collection($this->categoryService->getAll($relation, null, null, null, null, $conds, false, $row, true));

        // changing item arr object with new format
        $changedObj = $categories;

        if ($conds['order_by']) {
            $dataArr = [
                'categories' => $changedObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedDate' => $conds['selected_date'],
            ];
        } else {
            $dataArr = [
                'categories' => $changedObj,
                'search' => $conds['searchterm'],
                'selectedDate' => $conds['selected_date'],
            ];
        }


        return $dataArr;
    }

    public function prepareCategoryReportShowData($id)
    {
        $category = $this->categoryService->get($id, ['cover', 'icon']);
        $dataArr = [
            "category" => $category,
        ];
        return $dataArr;
    }

    public function prepareCategoryReportCsvExportData()
    {
        $filename = newFileNameForExport($this->csvFileName);
        return (new CategoryReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
