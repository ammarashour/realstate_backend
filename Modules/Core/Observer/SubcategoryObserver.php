<?php

namespace Modules\Core\Observer;

use Illuminate\Support\Facades\Storage;
use Modules\Core\Entities\Category\Subcategory;

class SubcategoryObserver
{
    public function deleted(Subcategory $core_category)
    {
        Storage::delete('public/category/' . $core_category->cover);
    }
}
