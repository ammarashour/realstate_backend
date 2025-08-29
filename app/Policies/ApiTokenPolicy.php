<?php

namespace App\Policies;

use App\Models\ApiToken;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Constants\Constants;

class ApiTokenPolicy extends PsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $module = Constants::apiTokenModule;
        $model = ApiToken::class;
        parent::__construct($module, $model);
    }
}
