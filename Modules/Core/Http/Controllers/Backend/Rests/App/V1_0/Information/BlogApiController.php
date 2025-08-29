<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Information;

use App\Exceptions\PsApiException;
use App\Http\Contracts\Blog\BlogServiceInterface;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Information\BlogApiResource;

class BlogApiController extends PsApiController
{
    protected $blogApiRelation;

    public function __construct(protected BlogServiceInterface $blogService,
        protected MobileSettingServiceInterface $mobileSettingService)
    {
        parent::__construct();
        $this->blogApiRelation = ['city', 'cover'];
    }

    public function search(Request $request)
    {
        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        // Get Blogs
        $data = BlogApiResource::collection(
                    $this->blogService->getAll(
                        $this->blogApiRelation,
                        Constants::publish,
                        $limit, $offset, 1, null, $conds
                    )
                );

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);

    }

    public function detail(Request $request)
    {
        // Get Blog Id
        $id = $this->getBlogId($request);

        // Get Blog Data
        $blog = $this->blogService->get($id, $this->blogApiRelation);

        return responseDataApi(new BlogApiResource($blog));
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////
    private function getBlogId($request)
    {
        if (! empty($request->id)) {
            return $request->id;
        }

        if (! empty($request->blogId)) {
            return $request->blogId;
        }

        $_err_message = __('core__api_record_not_found', [], $request->language_symbol);
        throw new PsApiException($_err_message, Constants::notFoundStatusCode);
    }

    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
    }

    private function getFilterConditions($request)
    {
        return [
            'searchterm' => $request->keyword,
            'location_city_id' => $request->location_city_id,
            'order_by' => $request->order_by,
            'order_type' => $request->order_type,
        ];
    }
}
