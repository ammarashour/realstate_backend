<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreAbout;

class CoreAboutPolicy extends PsPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = Constants::aboutModule;
        $model = CoreAbout::class;
        parent::__construct($module, $model);
    }
}
