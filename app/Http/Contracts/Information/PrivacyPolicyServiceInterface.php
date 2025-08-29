<?php

namespace App\Http\Contracts\Information;

use App\Http\Contracts\Core\PsInterface;

interface PrivacyPolicyServiceInterface extends PsInterface
{
    public function save($privacyPolicyData);

    public function update($privacyPolicyData);

    public function get($id = null);
}