<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface CoreKeyCounterServiceInterface extends PsInterface
{
    public function get($id = null, $conds = null);

    public function generate($code);
}
