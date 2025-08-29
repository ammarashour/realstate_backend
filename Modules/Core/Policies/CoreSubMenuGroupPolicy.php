<?php

namespace Modules\Core\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Constants\Constants;
use App\Policies\PsPolicy;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;

class CoreSubMenuGroupPolicy extends PsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = Constants::coreSubMenuModule;
        $model = CoreSubMenuGroup::class;
        parent::__construct($module, $model);
    }
}
