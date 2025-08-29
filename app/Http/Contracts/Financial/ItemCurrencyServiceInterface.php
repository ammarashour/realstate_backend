<?php

namespace App\Http\Contracts\Financial;

use App\Http\Contracts\Core\PsInterface;

interface ItemCurrencyServiceInterface extends PsInterface
{
    public function save($itemCurrencyData);

    public function update($id, $itemCurrencyData);

    public function getAll($status = null, $isDefault = null, $limit = null, $offset = null, $noPagination = null,  $pagPerPage = null, $conds = null);

    public function get($id = null, $conds = null);

    public function delete($id);

    public function setStatus($id, $status);

    public function defaultChange($id, $status);

    public function import($file);
}
