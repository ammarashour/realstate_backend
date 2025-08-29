<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\User;

use App\Config\ps_constant;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Controllers\PsController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Http\Requests\UpdateUserImageRequest;
use Modules\Core\Http\Requests\User\StoreUserRequest;
use Modules\Core\Http\Requests\User\UpdateUserRequest;
use Modules\Core\Http\Services\RoleService;
use Modules\Core\Transformers\Backend\Model\User\UserWithKeyResource;

class UserController extends PsController
{
    private const parentPath = 'core/user/';

    private const indexPath = self::parentPath.'Index';

    private const createPath = self::parentPath.'Create';

    private const editPath = self::parentPath.'Edit';

    private const profileEditPath = 'profile/Edit';

    private const indexRoute = 'user.index';

    private const createRoute = 'user.create';

    private const editRoute = 'user.edit';

    private const userCoverPhoto = 'user_cover_photo';

    private const imageKey = 'image';

    public function __construct(protected UserServiceInterface $userService,
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
        $this->handlePermissionWithModel(User::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(User::class, Constants::createAbility);

        $dataArr = $this->prepareCreateData();

        return renderView(self::createPath, $dataArr);
    }

    public function store(StoreUserRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Get Image File
            $cover = $request->file(self::userCoverPhoto);

            $relationalData = $this->prepareCustomFieldsData($request);

            // Save User
            $this->userService->save(userData : $validData, userCoverPhoto : $cover, relationalData: $relationalData);

            // Success and Redirect
            return redirectView(self::indexRoute);
        } catch (\Exception $e) {
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = $this->userService->get($id);
        // check permission
        $this->handlePermissionWithModel($user, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $validData = $request->validated();

            // Get Image File
            $cover = $request->file(self::userCoverPhoto);

            $relationalData = $this->prepareCustomFieldsData($request);

            $this->userService->update(
                id: $id,
                userData: $validData,
                userCoverPhoto: $cover,
                relationalData : $relationalData
            );

            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $user = $this->userService->get($id);

            $this->handlePermissionWithModel($user, Constants::deleteAbility);

            $dataArr = $this->userService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {
            $user = $this->userService->get($id);

            $this->handlePermissionWithModel($user, Constants::editAbility);

            $status = $this->prepareStatusData($user);

            $this->userService->setStatus($id, $status);

            return redirectView(self::indexRoute, __('core__be_status_updated'));
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
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

    public function profileEdit($id)
    {
        if (Auth::user()->id != $id) {
            return redirect()->route('admin.index');
        }

        $dataArr = $this->prepareProfileEditData($id);

        return renderView(self::profileEditPath, $dataArr);
    }

    public function profileUpdate(UpdateUserRequest $request, $id)
    {
        try {
            $validData = $request->validated();

            // Get Image File
            $cover = $request->file(self::userCoverPhoto);

            $relationalData = $this->prepareCustomFieldsData($request);

            $this->userService->update(
                id: $id,
                userData: $validData,
                userCoverPhoto: $cover,
                relationalData : $relationalData
            );

            return redirect()->back();
        } catch (\Throwable $e) {
            return redirectView(null, $e->getMessage());
        }
    }

    public function replaceImage(UpdateUserImageRequest $request, $id)
    {
        try {
            // Get Image File
            $cover = $request->file(self::imageKey);

            $this->userService->replaceImage($id, $cover);

            return redirect()->back();

        } catch (\Exception $e) {
            return redirectView(null, $e->getMessage());
        }
    }

    public function deleteImage($id)
    {
        try {
            $this->userService->deleteImage($id);

            return redirect()->back();

        } catch (\Exception $e) {
            return redirectView(null, $e->getMessage());
        }
    }

    public function screenDisplayUiStore(Request $request)
    {
        makeColumnHideShown($request);

        return redirect()->back();
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
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
            'role_id' => $request->input('role_filter') == 'all' ? null : $request->input('role_filter'),
            'date_range' => $request->input('date_filter') == 'all' ? null : $date_range,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        // manipulate category data
        $relation = [
            'role',
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation',
        ];

        $users = UserWithKeyResource::collection($this->userService->getAll(relation : $relation,
            status : null,
            isBanned: false,
            conds: $conds,
            limit : null,
            offset : null,
            condsIn : null,
            noPagination : false,
            pagPerPage: $row));

        //roles
        $roles = $this->roleService->getRoles();

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::user, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createUser' => 'create-user',
        ];

        return [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'roles' => $roles,
            'users' => $users,
            'selectedRole' => $conds['role_id'],
            'selectedDate' => $conds['date_range'],
            'usrIsVerifyBlueMark' => Constants::usrIsVerifyBlueMark,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareCreateData()
    {
        $roles = $this->roleService->getRoles();
        $customizeHeader = $this->customFieldService->getAll(withNoPag: true, isDelete: 0, code: Constants::user);
        $customizeDetail = $this->customFieldAttributeService->getAll(coreKeysIds: $customizeHeader->pluck('core_keys_id')->toArray());
        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::user,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        return [
            'roles' => $roles,
            'customizeHeaders' => $customizeHeader,
            'customizeDetails' => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
        ];

    }

    private function prepareEditData($id)
    {
        $dataWithRelation = ['userRelation'];

        $user = $this->userService->get($id, null, $dataWithRelation);

        $roles = $this->roleService->getRoles();

        $coreFieldFilterSettings = $this->coreFieldService->getAll(code: Constants::user,
            relation: null, limit: null, offset: null, isDel: 0, withNoPag: 1
        );

        $customizeHeader = $this->customFieldService->getAll(withNoPag: true, isDelete: 0, code: Constants::user);
        $customizeDetail = $this->customFieldAttributeService->getAll(coreKeysIds: $customizeHeader->pluck('core_keys_id')->toArray());

        $conds = [
            'module_name' => Constants::user,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = CoreField::where($conds)->get();

        $validation = [];
        foreach ($core_headers as $core_header) {
            if ($core_header->field_name == 'user_cover_photo') {
                array_push($validation, 'user_cover_photo');
            }
        }

        return [
            'user' => $user,
            'roles' => $roles,
            'customizeHeaders' => $customizeHeader,
            'customizeDetails' => $customizeDetail,
            'coreFieldFilterSettings' => $coreFieldFilterSettings,
            'validation' => $validation,
        ];

    }

    private function prepareStatusData($user)
    {
        return $user->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }

    private function prepareBanData($user)
    {
        return $user->is_banned == Constants::Ban
            ? Constants::unBan
            : Constants::Ban;
    }

    private function prepareCustomFieldsData($request)
    {
        // Retrieve the 'relation' input as an array of strings
        $relationsInput = $request->input('user_relation', []);

        // Retrieve the 'relation' files as an array of files
        $relationsFiles = !empty($request->allFiles()['user_relation']) ? $request->allFiles()['user_relation'] : [];

        // Merge the input and files arrays, preserving keys
        return array_merge($relationsInput, $relationsFiles);
    }

    private function prepareProfileEditData($id)
    {
        $user = $this->userService->get($id);
        $roles = $this->roleService->getRoles();
        $userRoles = $user->role_id;

        return [
            'user' => $user,
            'roles' => $roles,
            'yourPermissions' => $userRoles,
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
