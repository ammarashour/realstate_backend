<?php

namespace Modules\Core\Http\Services\Information;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Information\CorePrivacyPolicy;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Transformers\Backend\Model\Information\PrivacyPolicyWithKeyResource;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Information\PrivacyPolicyServiceInterface;

class PrivacyPolicyService extends PsService implements PrivacyPolicyServiceInterface
{

    public function __construct(protected ImageServiceInterface $imageService) {}

    public function save($privacyPolicyData)
    {
        DB::beginTransaction();

        try {
            $this->savePrivacyPolicy($privacyPolicyData);

        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

    }

    public function update($privacyPolicyData)
    {
        $html = $privacyPolicyData['content'];
        preg_match_all('/<img[^>]+\/?>/i', $html, $imageTags);
        $allImageTags = join(',', $imageTags[0]);
        preg_match_all('#([^/\'"=]*?[.](?:gif|jpeg|jpg|png))\b#i', $allImageTags, $imageTags);
        $imgSrcArray = array_pop($imageTags);

        $imgParentId = 0;

        $images = $this->imageService->getAll($imgParentId, 'privacy_policy');


        foreach ($images as $image) {
            if (!in_array($image->img_path, $imgSrcArray)) {
                $image = $this->imageService->delete($image->img_path);
            }
        }

        DB::beginTransaction();
        try {
           $this->updatePrivacyPolicy($privacyPolicyData);

            DB::commit();
        } catch (\Throwable $e) {
            dd($e->getMessage());
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id = null)
    {
        $privacy_policy = CorePrivacyPolicy::when($id, function ($q, $id) {
            $q->where(CorePrivacyPolicy::id, $id);
        })->first();
        return $privacy_policy;
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function savePrivacyPolicy($privacyPolicyData)
    {
        $privacy_policy = new CorePrivacyPolicy();
        $privacy_policy->fill($privacyPolicyData);
        $privacy_policy->added_user_id = Auth::user()->id;
        $privacy_policy->save();
    }
    private function updatePrivacyPolicy($privacyPolicyData)
    {
        $privacy_policy = $this->get();
        $privacy_policy->added_user_id = Auth::user()->id;
        $privacy_policy->update($privacyPolicyData);
    }
}
