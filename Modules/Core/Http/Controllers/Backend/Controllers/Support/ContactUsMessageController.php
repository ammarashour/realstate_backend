<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Support;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use App\Http\Contracts\Support\ContactUsMessageServiceInterface;
use Illuminate\Http\Request;
use Modules\Core\Transformers\Backend\Model\Support\ContactUsMessageWithKeyResource;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Support\Contact;

class ContactUsMessageController extends PsController
{
    private const parentPath = "contact_us_message";
    private const indexPath = self::parentPath . "/Index";
    private const createPath = self::parentPath . "/Create";
    private const editPath = self::parentPath . "/Edit";
    private const indexRoute = "contact_us_message.index";
    private const createRoute = "contact_us_message.create";
    private const editRoute = "contact_us_message.edit";


    public function __construct(protected ContactUsMessageServiceInterface $contactService)
    {
        parent::__construct();
    }

    public function index()
    {
        // check permission
        $this->handlePermissionWithModel(Contact::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData();

        return renderView(self::indexPath, $dataArr);
    }

    public function show($id)
    {
        // check permission start
        $contact = $this->contactService->get(null, $id);
        $this->handlePermissionWithModel($contact, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function edit($id)
    {
        // check permission start
        $contact = $this->contactService->get(null, $id);
        $this->handlePermissionWithModel($contact, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function destroy($id)
    {
        try {
            $contact = $this->contactService->get($id);

            $this->handlePermissionWithModel($contact, Constants::deleteAbility);

            $dataArr = $this->contactService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function markAllAsRead()
    {
        $this->contactService->markAllAsRead();
        return renderView(self::indexPath);
    }

    public function getContactFormTitle()
    {
        $dataArr =  $this->prepareContactDataFromTitle();
        return $dataArr;
    }

    public function multipleDelete(Request $request)
    {
        $this->contactService->multiDelete($request->ids);
        return renderView(self::editPath);
    }

    public function csvExport()
    {
        return $this->contactService->csvExport();
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData()
    {
        $contactRelation = ['owner', 'editor'];
        $this->markAllAsSeen();
        $contacts = ContactUsMessageWithKeyResource::collection($this->contactService->getAll($contactRelation));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::contact, $this->controlFieldArr());

        return [
            'contacts' => $contacts,
            'showContactCols' => $columnAndColumnFilter[ps_constant::showCoreField],
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
        ];
    }

    private function prepareEditData($id)
    {
        $contactRelation = ['owner'];
        $contact = $this->contactService->get($contactRelation, $id);

        $contact->is_read = 1;
        $contact->is_seen = 1;
        $contact->update();
        $contact = new ContactUsMessageWithKeyResource($contact);

        return [
            'contact' => $contact,
        ];
    }

    private function prepareContactDataFromTitle()
    {
        $contactRelation = ['owner', 'editor'];
        $contacts = ContactUsMessageWithKeyResource::collection($this->contactService->getAll($contactRelation, null, 5, 0));

        $count = $this->countUnseen();

        $this->markAllAsSeen();

        $dataArr = [
            "contacts" => $contacts,
            "unseenCount" => $count
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

    private function countUnseen()
    {
        $contacts = $this->contactService->getAll(null, [Contact::isSeen => 0]);
        return $contacts->count();
    }

    private function markAllAsSeen()
    {
        $contacts = $this->contactService->getAll();

        foreach ($contacts as $contact) {
            $contact->update([Contact::isSeen => 1]);
        }
    }

}
