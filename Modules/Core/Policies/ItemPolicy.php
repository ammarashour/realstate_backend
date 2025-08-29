<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item\Item;

class ItemPolicy extends PsPolicy
{
    public function __construct()
    {
        $module = Constants::itemModule;
        $model = Item::class;
        parent::__construct($module, $model);
    }
}
