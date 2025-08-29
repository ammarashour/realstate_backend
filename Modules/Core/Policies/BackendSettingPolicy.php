<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\BackendSetting;

class BackendSettingPolicy extends PsPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = Constants::backendSettingModule;
        $model = BackendSetting::class;
        parent::__construct($module, $model);
    }
}
