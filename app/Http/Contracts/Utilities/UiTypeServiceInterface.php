<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface UiTypeServiceInterface extends PsInterface
{
    function getAll($coreKeyIds, $limit, $offset);
}
