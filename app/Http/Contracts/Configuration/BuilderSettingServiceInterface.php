<?php

namespace App\Http\Contracts\Configuration;

use App\Http\Contracts\Core\PsInterface;

interface BuilderSettingServiceInterface extends PsInterface
{
    public function get($id = null);

    public function update($id, $builderSettingData);

    public function handleProjectReset();
}