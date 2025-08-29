<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface CoreKeyServiceInterface extends PsInterface
{
    public function save($coreKeyData, $code);

    public function update($id, $coreKeyData);

    public function delete($id = null, $conds = null);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll($relations = null, $limit = null, $offset = null, $conds = null, $paymentId = null);
}
