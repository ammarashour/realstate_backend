<?php

namespace Modules\Core\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Core\Constants\Constants;
use App\Policies\PsPolicy;
use Modules\Core\Entities\Localization\LanguageString;
use Modules\Core\Entities\Localization\Language;

class LanguageStringPolicy extends PsPolicy
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
