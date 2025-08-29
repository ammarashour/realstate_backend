<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Category;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsApiController;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Transformers\Api\App\V1_0\Category\CategoryApiResource;

class CategoryApiController extends PsApiController
{
    public function __construct(protected CategoryServiceInterface $categoryService,
        protected LanguageServiceInterface $languageService,
        protected MobileSettingServiceInterface $mobileSettingService)
    {
        parent::__construct();
    }

    public function search(Request $request)
    {
        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        // Get Language
        $langConds = $this->prepareLanguageData($request);
        $language = $this->languageService->get(null, $langConds);

        // Get Categories
        $categories = $this->categoryService->getAll(null, Constants::publish, $language->id, $limit, $offset, $conds, null, null, $conds);
        $data = CategoryApiResource::collection($categories);

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    ///-----------------------------------------------------------------
    // Prepare Data
    ///-----------------------------------------------------------------
    private function prepareLanguageData($languageData)
    {
        return ['symbol' => $languageData->language_symbol ?? 'en'];
    }

    ///-----------------------------------------------------------------
    // Prepare Data
    ///-----------------------------------------------------------------
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
            'order_by' => $request->order_by ?? Category::ordering,
            'order_type' => $request->order_type ?? ps_constant::ascending,
        ];
    }
}
