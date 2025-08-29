<?php

namespace Modules\Core\Http\Services\Support;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ContactUsMessage\Exports\ContactUsMessageExport;
use App\Http\Contracts\Support\ContactUsMessageServiceInterface;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Support\Contact;
use Modules\Core\Exports\ContactUsMessageExport as ExportsContactUsMessageExport;

class ContactService extends PsService implements ContactUsMessageServiceInterface
{

    public function __construct() {}

    public function save($contactUsMsgData)
    {
        DB::beginTransaction();

        try {
            $contact = $this->saveContactUsMsg($contactUsMsgData);

            DB::commit();
            return $contact;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $contactUsMsgData)
    {
        DB::beginTransaction();

        try {

            $contact = $this->updateContactUsMsg($id, $contactUsMsgData);

            DB::commit();
            return $contact;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function get($relation = null, $id = null)
    {

        $contact = Contact::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($id, function ($q, $id) {
                $q->where(Contact::id, $id);
            })->first();
        return $contact;
    }

    public function getAll($relation = null, $conds = null, $limit = null, $offset = null)
    {

        $contacts = Contact::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($conds, function ($q, $conds) {
                $q->where($conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->orderBy('id', 'desc')
            ->get();
        return $contacts;
    }

    public function markAllAsRead()
    {
        $contacts = $this->getAll();

        foreach ($contacts as $contact) {
            $contact->update([Contact::isRead => 1]);
        }
    }

    public function multiDelete($ids)
    {
        $contacts = $this->getAll(null, ['id' => $ids]);
        $contactIds = $contacts->pluck('id')->toArray();

        Contact::destroy($contactIds);
    }

    public function delete($id)
    {
        try{
        
        $this->deleteContactUsMsg($id);

        $msg = 'The Contact has been deleted successfully.';
        return [
            "msg" => $msg,
            "flag" => Constants::success
        ];
        }catch(\Throwable $e){
            throw $e;
        }
        
    }

    public function csvExport()
    {

        $filename = newFileNameForExport("contact_us_message");
        return (new ExportsContactUsMessageExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveContactUsMsg($contactUsMsgData)
    {
        $contact = new Contact();
        $contact->fill($contactUsMsgData);
        $contact->added_user_id = Auth::user()->id;
        $contact->save();

        return $contact;
    }
    private function updateContactUsMsg($id, $contactUsMsgData)
    {
        $contact = $this->get($id);
        $contact->updated_user_id = Auth::user()->id;
        $contact->update($contactUsMsgData);

        return $contact;
    }
    private function deleteContactUsMsg($id)
    {
        $contact = $this->get(null, $id);
        $contact->delete();
    }
}
