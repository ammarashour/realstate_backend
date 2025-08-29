<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface ColorServiceInterface extends PsInterface
{
    public function save($colorData);

    public function update($id, $colorData);

    public function delete($id);

    public function get($id = null, $key = null);

    public function getAll($limit = null, $offset = null, $conds = null, $noPagination = null,  $pagPerPage = null);

}
