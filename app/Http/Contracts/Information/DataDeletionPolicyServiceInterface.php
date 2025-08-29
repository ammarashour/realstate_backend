<?php

namespace App\Http\Contracts\Information;

use App\Http\Contracts\Core\PsInterface;

interface DataDeletionPolicyServiceInterface extends PsInterface
{
    public function save($dataDeletionPolicyData);

    public function update($id, $dataDeletionPolicyData);

    public function get($id = null);
}