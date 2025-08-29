<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface CacheKeyServiceInterface extends PsInterface
{
    public function save($cacheKeyData);

    public function delete($id);

    public function deleteAll($baseKey1 = null, $conds = null);

    public function get($id = null, $conds = null);

    // public function getAll($baseKey1 = null, $conds = null);
}
