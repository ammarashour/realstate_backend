<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Image;

use App\Http\Contracts\Image\ImageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreImage;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Log;
use Modules\Core\Http\Services\ImageService;

class ImageController extends Controller
{
    public function __construct(protected ImageService $imageService,
                                protected ImageServiceInterface $imageServiceInterface)
    {}

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
    
        // New Logic with New Image Service Interface
        // Get Image File
        // $file = $request->file('image');
        // $extension = $file->getClientOriginalExtension();

        // $imgData = [
        //     'img_type' => $request->uploadType,
        //     'ordering' => $request->ordering,
        //     'img_desc' => $request->img_desc,
        //     // 'img_path' => $request->img_path,
        //     'updated_user_id' => $request->updated_user_id
        // ];
        
        // Log::info("hello");
        
        // if ($extension !== 'ico' && $request) {
        //     $request->validate([
        //         'image' => 'nullable|sometimes|image'
        //     ]);
        // }


        // $this->imageServiceInterface->updateImage($id, $file, $imgData);

        // Original Code
        $this->imageService->update($request, $id);
        return redirect()->back();
    }

    public function updateVideo(Request $request, $id)
    {
        $this->imageService->updateVideo($request, $id);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->imageService->destroy($id);

        // $msg = 'The image has been deleted successfully.';
        $msg = __('core_images_tb_del');

        return redirectView(null, $msg, Constants::danger);
    }
}
