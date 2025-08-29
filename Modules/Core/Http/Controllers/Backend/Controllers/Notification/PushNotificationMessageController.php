<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Notification;

use App\Config\ps_constant;
use App\Http\Contracts\Notification\PushNotificationMessageServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Notification\PushNotificationMessage;
use Modules\Core\Http\Requests\Notification\StorePushNotificationMessageRequest;
use Modules\Core\Transformers\Backend\Model\Notification\PushNotificationMessageWithKeyResource;

class PushNotificationMessageController extends PsController
{
    private const parentPath = 'push_notification_message/';

    private const indexPath = self::parentPath.'Index';

    private const createPath = self::parentPath.'Create';

    private const editPath = self::parentPath.'Edit';

    private const indexRoute = 'push_notification_message.index';

    private const createRoute = 'push_notification_message.create';

    private const imageKey = 'cover';

    protected $successFlag;

    protected $dangerFlag;

    protected $code;

    public function __construct(
        protected PushNotificationMessageServiceInterface $pushNotificationMessageService,
        protected CoreFieldServiceInterface $coreFieldService
    ) {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(PushNotificationMessage::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        // check permission start
        $this->handlePermissionWithModel(PushNotificationMessage::class, Constants::createAbility);

        return renderView(self::createPath);
    }

    public function store(StorePushNotificationMessageRequest $request)
    {
        try {
            // Validate the request data
            $validData = $request->validated();

            // Get Image File
            $file = $request->file(self::imageKey);

            // Save PushNotificationMessage
            $this->pushNotificationMessageService->save(
                pushNotificationMessageData: $validData,
                pushNotificationMessageImage: $file
            );

            // Success and Redirect
            return redirectView(self::indexRoute);

        } catch (\Exception $e) {
            dd($e->getMessage());

            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pushNotificationMessage = $this->pushNotificationMessageService->get($id);

            $this->handlePermissionWithModel($pushNotificationMessage, Constants::deleteAbility);

            $dataArr = $this->pushNotificationMessageService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
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
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'order_by' => $request->input('sort_field') ?? null,
            'order_type' => $request->input('sort_order') ?? null,
        ];

        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $relation = ['owner', 'editor', 'cover'];
        $push_notification_messages = PushNotificationMessageWithKeyResource::collection($this->pushNotificationMessageService->getAll(
            relation: $relation,
            conds: $conds,
            noPagination: Constants::no,
            pagPerPage: $row));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::pushNotificationMessage, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createPushNotificationMessage' => 'create-pushNotificationMessage',
        ];

        $dataArr = [
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'push_notification_messages' => $push_notification_messages,
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];

        return $dataArr;

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
