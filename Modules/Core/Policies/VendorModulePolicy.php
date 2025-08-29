<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Menu\VendorModule;

class VendorModulePolicy extends PsPolicy
{
    public function __construct()
    {
        $module = Constants::vendorModuleModule;
        $model = VendorModule::class;
        parent::__construct($module, $model);
    }
}
