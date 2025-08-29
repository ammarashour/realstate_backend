<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\User;

use App\Config\ps_constant;
use App\Http\Contracts\User\BlueMarkUserServiceInterface;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Controllers\PsController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Requests\User\UpdateBlueMarkReqeust;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\RoleService;
use Modules\Core\Transformers\Backend\NoModel\User\BlueMarkUserWithKeyResource;

class BlueMarkUserController extends PsController
{
    private const parentPath = 'bluemarkuser';

    private const indexPath = self::parentPath.'/Index';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(
        protected BlueMarkUserServiceInterface $blueMarkUserService,
        protected RoleService $roleService,
        protected UserServiceInterface $userService,
        )
    {

        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::blueMarkUserModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::blueMarkUserModule, ps_constant::updatePermission, Auth::id());

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateBlueMarkReqeust $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->blueMarkUserService->update($id, $validatedData);

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        // check permission
        try {
            $this->handlePermissionWithoutModel(Constants::blueMarkUserModule, ps_constant::deletePermission, Auth::id());

            $dataArr = $this->blueMarkUserService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------
    private function prepareIndexData($request)
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
            'bluemark_status' => $request->input('status_filter') === 'all' ? null : $request->input('status_filter'),
            'bluemark_updated_at' => $request->input('date_filter') === 'all' ? null : $date_range,
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate blog data
        $users = BlueMarkUserWithKeyResource::collection($this->blueMarkUserService->getAll(
            conds : $conds,
            noPagination : false,
            pagPerPage : $row));

        $roles = $this->roleService->getRoles();

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::user, $this->controlFieldArr());

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'users' => $users,
            'roles' => $roles,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'selectedStatus' => $conds['bluemark_status'],
            'selectedDate' => $conds['bluemark_updated_at'],
            'usrIsVerifyBlueMark' => Constants::usrIsVerifyBlueMark,
            'usrBlueMarkNote' => Constants::usrBlueMarkNote,
            'verifyBlueMarkList' => $this->getBlueMarkStatus(),
        ];

    }

    private function prepareEditData($id)
    {
        $relation = ['userRelation'];
        $user = $this->userService->get($id, null, $relation);

        $bluemarkStatusList = $this->getBlueMarkStatus();

        $blueMarkNote = '';
        $blueMarkStatus = 1;
        $blueMarkStatusId = '';
        foreach ($user->userRelation as $relation) {
            if ($relation->core_keys_id == Constants::usrBlueMarkNote) {
                $blueMarkNote = $relation->value;
            }
            if ($relation->core_keys_id == Constants::usrIsVerifyBlueMark) {
                $blueMarkStatus = $relation->value;
                $blueMarkStatusId = $relation->id;
            }
        }

        return [
            'user' => $user,
            'bluemarkStatusList' => $bluemarkStatusList,
            'blueMarkNote' => $blueMarkNote,
            'blueMarkStatus' => $blueMarkStatus,
            'blueMarkStatusId' => $blueMarkStatusId,
        ];
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function getBlueMarkStatus()
    {
        $verifyBlueMarkList = [];

        $apply = new \stdClass;
        $apply->id = 1;
        $apply->name = __('bluemarkuser__be_applied_label');
        array_push($verifyBlueMarkList, $apply);

        $pending = new \stdClass;
        $pending->id = 2;
        $pending->name = __('bluemarkuser__be_pending_label');
        array_push($verifyBlueMarkList, $pending);

        $reject = new \stdClass;
        $reject->id = 3;
        $reject->name = __('bluemarkuser__be_rejected_label');
        array_push($verifyBlueMarkList, $reject);

        return $verifyBlueMarkList;
    }

    private function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), 'action', 'Action', false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        return $controlFieldArr;
    }
}
