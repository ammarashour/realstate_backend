<?php

namespace Modules\Core\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Constants\Constants;
use App\Policies\PsPolicy;
use Modules\Core\Entities\Configuration\FrontendSetting;

class FrontendSettingPolicy extends PsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = Constants::languageStringModule;
        $model = Language::class;
        parent::__construct($module, $model);
    }
}
