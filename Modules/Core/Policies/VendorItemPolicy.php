<?php

namespace Modules\Core\Policies;

use App\Policies\PsVendorPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Item;

class VendorItemPolicy extends PsVendorPolicy
{
    public function __construct()
    {
        $module = constants::vendorItemModule;
        $model = Item::class;
        parent::__construct($module, $model);
    }
}
