<?php

namespace Modules\Core\Policies;

use App\Policies\PsPolicy;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

class AvailableCurrencyPolicy extends PsPolicy
{
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = Constants::availableCurrencyModule;
        $model = AvailableCurrency::class;
        parent::__construct($module, $model);
    }
}
