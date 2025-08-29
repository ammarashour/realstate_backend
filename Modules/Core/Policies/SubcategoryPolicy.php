<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Subcategory;

class SubcategoryPolicy extends PsPolicy
{

    public function __construct()
    {
        $module = Constants::subCategoryModule;
        $model = Subcategory::class;
        parent::__construct($module, $model);
    }
}
