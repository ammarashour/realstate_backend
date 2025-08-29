<?php

namespace Modules\Core\Policies;

use App\Config\ps_constant;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\PsPolicy;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;

class CustomFieldAttributePolicy extends PsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = ps_constant::customFieldAttributeModule;
        $model = CustomFieldAttribute::class;
        parent::__construct($module, $model);
    }
}
