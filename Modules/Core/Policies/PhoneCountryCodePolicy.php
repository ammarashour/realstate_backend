<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\PhoneCountryCode;

class PhoneCountryCodePolicy extends PsPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = Constants::phoneCountryCodeModule;
        $model = PhoneCountryCode::class;
        parent::__construct($module, $model);
    }
}
