<?php

namespace Modules\Core\Http\Services\Information;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Information\CoreDataDeletion;
use Illuminate\Support\Facades\Auth;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Information\DataDeletionPolicyServiceInterface;

class DataDeletionPolicyService extends PsService implements DataDeletionPolicyServiceInterface
{
    public function __construct(protected ImageServiceInterface $imageService) {}

    public function save($dataDeletionPolicyData)
    {
        DB::beginTransaction();
        try {
            $this->saveDataDeletionPolicy($dataDeletionPolicyData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $dataDeletionPolicyData)
    {
        $html = $dataDeletionPolicyData['content'];
        preg_match_all('/<img[^>]+\/?>/i', $html, $imageTags);
        $allImageTags = join(',', $imageTags[0]);
        preg_match_all('#([^/\'"=]*?[.](?:gif|jpeg|jpg|png))\b#i', $allImageTags, $imageTags);
        $imgSrcArray = array_pop($imageTags);

        $imgParentId = 0;

        $images = $this->imageService->getAll($imgParentId, 'data_deletion_policy');


        foreach ($images as $image) {
            if (!in_array($image->img_path, $imgSrcArray)) {
                $image = $this->imageService->delete($image->img_path);
            }
        }

        DB::beginTransaction();
        try {
            $this->updateDataDeletionPolicy($id, $dataDeletionPolicyData);

            DB::commit();
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id = null)
    {
        return CoreDataDeletion::when($id, function($query, $id) {
                $query->where(CoreDataDeletion::id, $id);
            })->first();
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    // Database
    //-------------------------------------------------------------------

    private function saveDataDeletionPolicy($dataDeletionPolicyData) 
    {
        $data_deletion_policy = new CoreDataDeletion();
        $data_deletion_policy->fill($dataDeletionPolicyData);
        $data_deletion_policy->added_user_id = Auth::user()->id;
        $data_deletion_policy->save();
    }
    private function updateDataDeletionPolicy($id, $dataDeletionPolicyData)
    {
        $data_deletion_policy = $this->get();
        $data_deletion_policy->updated_user_id = Auth::user()->id;
        $data_deletion_policy->update($dataDeletionPolicyData);
    }
}
