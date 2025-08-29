<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface SettingServiceInterface extends PsInterface
{
    public function update($id, $settingData);

    public function get($id = null, $env = null);
}
