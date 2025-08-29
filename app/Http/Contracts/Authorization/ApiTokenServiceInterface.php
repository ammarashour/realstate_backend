<?php

namespace App\Http\Contracts\Authorization;

use App\Http\Contracts\Core\PsInterface;

interface ApiTokenServiceInterface extends PsInterface
{
    public function getAll($status = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null, $abilities = null);

    public function get($id = null, $conds = null);

    public function delete($id);

}