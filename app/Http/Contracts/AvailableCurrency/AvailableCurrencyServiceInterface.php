<?php

namespace App\Http\Contracts\AvailableCurrency;

use App\Http\Contracts\Core\PsInterface;

interface AvailableCurrencyServiceInterface extends PsInterface
{
    public function save($availableCurrencyData);

    public function update($id, $availableCurrencyData);

    public function delete($id);

    public function get($id = null, $relation = null, $conds = null);

    public function getAll(
        $relation = null,
        $status = null,
        $limit = null,
        $offset = null,
        $noPagination = null,
        $pagPerPage = null,
        $conds = null);

    public function setStatus($id, $status);

    public function defaultChange($id);
}
