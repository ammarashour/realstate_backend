<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;

class CategoryPolicy extends PsPolicy
{
    public function __construct()
    {
        $module = Constants::categoryModule;
        $model = Category::class;
        parent::__construct($module, $model);
    }
}
