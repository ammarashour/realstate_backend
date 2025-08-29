<?php

namespace Modules\Core\Http\Services\Information;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\CoreAbout;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Information\AboutServiceInterface;


class AboutService extends PsService implements AboutServiceInterface
{
    public function __construct(protected ImageServiceInterface $imageService,
    protected UserAccessApiTokenService $userAccessApiTokenService)
    {}

    public function save($aboutData)
    {
        DB::beginTransaction();
        try {

            $about = $this->saveAbout($aboutData);

            DB::commit();
        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
        return $about;
    }

    public function update($id, $aboutData, $file)
    {
        DB::beginTransaction();
        try {

            $about = $this->updateAbout($id, $aboutData);

            $this->updateImage($id, $file);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
        return $about;
    }

    public function get($id = null, $relation = null)
    {
        $about = CoreAbout::when($id, function ($q, $id) {
            $q->where(CoreAbout::id, $id);
        })
        ->when($relation, function ($q, $relation) {
            $q->with($relation);
        })->first();
        return $about;
    }

     ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveAbout($aboutData)
    {
        $blog = new CoreAbout();
        $blog->fill($aboutData);
        $blog->added_user_id = Auth::user()->id;
        $blog->save();

        return $blog;
    }
    private function updateAbout($id, $aboutData)
    {
        $about = $this->get($id);
        $about->updated_user_id = Auth::user()->id;
        $about->update($aboutData);

        return $about;
    }

    private function updateImage($id, $file)
    {
        $about = $this->get($id, ['defaultPhoto']);
        $conds = [
            CoreImage::imgParentId => $id,
            CoreImage::imgType => "about",
        ];
        $image = $this->imageService->get($conds);
        // if image, delete existing file
        deleteImage($image);

        $this->imageService->update($about->defaultPhoto->id, $file, $conds);
    }
}
