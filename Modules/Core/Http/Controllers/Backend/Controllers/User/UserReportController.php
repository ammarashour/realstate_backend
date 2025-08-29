<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\User;

use Carbon\Carbon;
use App\Models\User;
use App\Config\ps_constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsController;
use Modules\Core\Exports\UserReportExport;
use Modules\Core\Exports\BuyerReportExport;
use Modules\Core\Http\Services\RoleService;
use Modules\Core\Exports\SellerReportExport;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use Modules\Core\Transformers\Backend\NoModel\User\UserReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\User\BuyerReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\User\SellerReportWithKeyResource;

class UserReportController extends PsController
{
    private const parentPath = 'user_report';

    private const indexPath = self::parentPath.'/Index';

    private const editPath = self::parentPath.'/Edit';

    private const parentBuyerReportPath = 'buyer_report';

    private const indexBuyerReportPath = self::parentBuyerReportPath.'/Index';

    private const editBuyerReportPath = self::parentBuyerReportPath.'/Edit';

    private const parentSellerReportPath = 'seller_report';

    private const indexSellerReportPath = self::parentSellerReportPath.'/Index';

    private const editSellerReportPath = self::parentSellerReportPath.'/Edit';

    private const buyerReport = 'buyer_report';

    private const sellerReport = 'seller_report';

    private const userReport = 'user_report';

    private const dailyActiveUserReport = 'daily_active_user_report';

    public function __construct(protected UserServiceInterface $userService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected CustomFieldServiceInterface $customFieldService,
        protected CustomFieldAttributeServiceInterface $customFieldAttributeService,
        protected RoleService $roleService)
    {
        parent::__construct();
    }

    // Buyer Report
    public function buyerReportIndex(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::buyerReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request, self::buyerReport, BuyerReportWithKeyResource::class);

        return renderView(self::indexBuyerReportPath, $dataArr);
    }

    public function buyerReportShow($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::buyerReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editBuyerReportPath, $dataArr);
    }

    public function buyerReportCsvExport()
    {
        return $this->userService->reportCsvExport(self::buyerReport, BuyerReportExport::class);
    }

    // Seller Report
    public function sellerReportIndex(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::sellerReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request, self::sellerReport, SellerReportWithKeyResource::class);

        return renderView(self::indexSellerReportPath, $dataArr);
    }

    public function sellerReportShow($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::sellerReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editSellerReportPath, $dataArr);
    }

    public function sellerReportCsvExport()
    {
        return $this->userService->reportCsvExport(self::sellerReport, SellerReportExport::class);
    }

    // User Report
    public function userReportIndex(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::userReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function userReportShow($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::userReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function userReportCsvExport()
    {
        // filename
        return $this->userService->reportCsvExport(self::userReport, UserReportExport::class);
    }

    // Daily Active User Report
    public function dailyActiveUserReportIndex(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::dailyActiveUserReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function dailyActiveUserReportShow($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::dailyActiveUserReportModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function dailyActiveUserReportCsvExport()
    {
        // filename
        return $this->userService->reportCsvExport(self::dailyActiveUserReport, UserReportExport::class);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------
    private function prepareIndexData($request, $reportType = null, $resource = UserReportWithKeyResource::class)
    {
        $date_range = null;
        if (! empty($request->input('date_filter')) && $request->input('date_filter') != 'all') {
            $start_date = $request->input('date_filter')[0];
            $end_date = $request->input('date_filter')[1];
            if (empty($end_date)) {
                $end_date = Carbon::now();
            }
            $date_range = [$start_date, $end_date];
        }

        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'role_id' => $request->input('role_filter') == 'all' ? null : $request->input('role_filter'),
            'date_range' => $request->input('date_filter') == 'all' ? null : $date_range,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $roles = $this->roleService->getRoles();

        // manipulate category data
        $relation = [
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation',
            'blue_mark',
        ];

        $users = $resource::collection($this->userService->getAll(relation : $relation,
            status : null,
            isBanned: false,
            conds: $conds,
            limit : null,
            offset : null,
            condsIn : null,
            noPagination : false,
            pagPerPage: $row,
            sort: null,
            report: $reportType
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::user, $this->controlFieldArr());

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'users' => $users,
            'roles' => $roles,
            'selectedDate' => $conds['date_range'],
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
        ];
    }

    private function prepareShowData($id)
    {
        $dataWithRelation = ['role'];

        $user = $this->userService->get($id, null, $dataWithRelation);

        $customizeHeader = $this->customFieldService->getAll(withNoPag: true, isDelete: 0, code: Constants::user);
        $customizeDetail = $this->customFieldAttributeService->getAll(coreKeysIds: $customizeHeader->pluck('core_keys_id')->toArray());

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::user,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'user' => $user,
            'customizeHeaders' => $customizeHeader,
            'customizeDetails' => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), 'action', 'Action', false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        return $controlFieldArr;
    }
}
