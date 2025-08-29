<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\VendorApplication;
use App\Http\Contracts\Vendor\VendorApplicationServiceInterface;
use App\Http\Contracts\User\UserServiceInterface;

class VendorApplicationService extends PsService implements VendorApplicationServiceInterface
{
    public function __construct(
        protected UserServiceInterface $userService,
    ) {}

    public function save($vendorApplicationData, $vendorId)
    {
        DB::beginTransaction();
        try {

            $this->storeVendorApplication($vendorApplicationData, $vendorId);
            DB::commit();
        } catch (\Throwable $e) {
            throw $e;
            DB::rollBack();
        }
    }

    public function update($id, $vendorApplicationData, $vendorId)
    {
        DB::beginTransaction();
        try {

            $this->updateVendorApplication($id, $vendorApplicationData, $vendorId);
            DB::commit();
        } catch (\Throwable $e) {
            throw $e;
            DB::rollBack();
        }
    }

    public function get($id = null, $vendorId = null)
    {
        $appliction = VendorApplication::when($id !== null, function ($query) use ($id) {
            $query->where(VendorApplication::id, $id);
        })->when($vendorId !== null, function ($query) use ($vendorId) {
            $query->where(VendorApplication::vendorId, $vendorId);
        })->first();

        return $appliction;
    }

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null)
    {
        $vendorApplications = VendorApplication::when($relations, function ($query, $relations) {
            $query->with($relations);
        })
            ->when($conds, function ($query, $conds) {
                $query->where($conds);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->latest()->get();
        return $vendorApplications;
    }

    public function delete($id)
    {
        try {

            $this->deleteVendorApplication($id);

            return [
                'msg' => __('core__be_delete_success'),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function downloadDocument($applicationId = null, $vendorId = null)
    {
        $application = $this->get($applicationId, $vendorId);

        $file_exist = File::exists(public_path() . '/document/' . $application->document);
        if ($file_exist) {
            $file = public_path('document/' . $application->document);
            return response()->download($file);
        }
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function storeVendorApplication($vendorApplicationData, $vendorId)
    {
        $vendorApplication = new VendorApplication();
        $vendorApplication->fill($vendorApplicationData);
        $vendorApplication->user_id = $vendorApplicationData['owner_user_id'];
        $vendorApplication->vendor_id = $vendorId;
        $vendorApplication->document = $this->storeDocument($vendorApplicationData);
        $vendorApplication->save();
    }

    private function storeDocument($vendorApplicationData)
    {
        if (isset($vendorApplicationData['document'])) {
            $document = $vendorApplicationData['document'];
            $fileName = uniqid() . "_." . $document->getClientOriginalExtension();
            $document->move(public_path() . '/document', $fileName);

            return $fileName;
        }
    }

    private function updateVendorApplication($id, $vendorApplicationData, $vendorId)
    {
        $vendorApplication = $this->get($id);
        if (isset($vendorApplicationData['document']) || !empty($vendorApplicationData['document'])) {
            $oldFile = public_path() . '/document/' . $vendorApplication->document;
            if (is_file($oldFile)) {
                unlink($oldFile);
            }
            $vendorApplication->document = $this->storeDocument($vendorApplicationData);
        }
        $vendorApplication->update($vendorApplicationData);
        $vendorApplication->vendor_id = $vendorId;
        $vendorApplication->updated_user_id = Auth::user()->id;
    }

    private function deleteVendorApplication($id)
    {
        $vendorApplication = $this->get($id);
        $vendorApplication->delete();
    }
}
