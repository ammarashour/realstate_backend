<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface PhoneCountryCodeServiceInterface extends PsInterface
{
    public function save($phoneCountryCodeData);

    public function update($id, $phoneCountryCodeData);

    public function getAll($status = null, $isDefault = null, $limit = null, $offset = null, $noPagination = null,  $pagPerPage = null, $conds = null);

    public function get($id);

    public function delete($id);

    public function setStatus($id, $status);

    public function defaultChange($id, $status);
}
