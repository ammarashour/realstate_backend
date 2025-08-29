<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface AdPostTypeServiceInterface extends PsInterface
{
    public function get($id = null, $conds = null);

    public function getAll($id = null, $conds = null);
}
