<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\User;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsController;
use Modules\Core\Http\Services\RoleService;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use Modules\Core\Transformers\Backend\NoModel\User\BannedUserWithKeyResource;

class BannedUserController extends PsController
{
    private const parentPath = 'banned_user';

    private const indexPath = self::parentPath.'/Index';

    private const createPath = self::parentPath.'/Create';

    private const editPath = self::parentPath.'/Edit';

    private const indexRoute = self::parentPath.'.index';

    private const createRoute = self::parentPath.'.create';

    private const editRoute = self::parentPath.'.edit';

    public function __construct(
        protected UserServiceInterface $userService,
        protected RoleService $roleService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected CustomFieldServiceInterface $customFieldService,
        protected CustomFieldAttributeServiceInterface $customFieldAttributeService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::bannedUserModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::bannedUserModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->prepareShowData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function ban($id)
    {
        try {
            $user = $this->userService->get($id);

            $this->handlePermissionWithModel($user, Constants::editAbility);

            $ban = $this->prepareBanData($user);

            $this->userService->ban($id, $ban);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
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
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate category data
        $relation = [
            'role',
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation',
        ];

        $users = BannedUserWithKeyResource::collection($this->userService->getAll(relation : $relation,
            status : null,
            isBanned: true,
            conds: $conds,
            limit : null,
            offset : null,
            condsIn : null,
            noPagination : false,
            pagPerPage: $row,
            sort: null,
            report: null
        ));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::user, $this->controlFieldArr());

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'users' => $users,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
        ];
    }

    private function prepareShowData($id)
    {
        $dataWithRelation = ['userRelation'];

        $user = $this->userService->get($id, null, $dataWithRelation);

        $roles = $this->roleService->getRoles();

        $customizeHeader = $this->customFieldService->getAll(withNoPag: true, isDelete: 0, code: Constants::user);
        $customizeDetail = $this->customFieldAttributeService->getAll(coreKeysIds: $customizeHeader->pluck('core_keys_id')->toArray());

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::user,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'user' => $user,
            'roles' => $roles,
            'customizeHeaders' => $customizeHeader,
            'customizeDetails' => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareBanData($user)
    {
        return $user->is_banned == Constants::Ban
            ? Constants::unBan
            : Constants::Ban;
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
