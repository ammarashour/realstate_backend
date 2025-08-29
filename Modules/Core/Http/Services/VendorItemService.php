<?php

namespace Modules\Core\Http\Services;

use Carbon\Carbon;
use App\Config\ps_config;
use App\Config\ps_constant;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item;

use Modules\Core\Http\Services\ItemService;


class  VendorItemService extends ItemService
{
    // protected ;

    // for Backend
    public function index($request)
    {
        $vendor_id = getVendorIdFromSession();
        $request->vendor_id = $vendor_id;
        $response = $this->getItemList($request);

        $categoriesWithCount = $this->categoryService->getAll(noPagination: Constants::yes);
        $defaultCategoryId = $categoriesWithCount[0]->id;
        $categoryId = $request->category_filter ?? $defaultCategoryId;
        // $conds['category_id'] = $categoryId;
        $conds['vendor_id'] = $vendor_id;
        foreach($categoriesWithCount as $category){
            $category->name = __($category->name);
            $category->count = $this->countCategory($category->id, $conds);
        }

        $response['categoriesWithCount'] = $categoriesWithCount;

        return $response;
    }
}
