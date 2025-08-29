<?php

namespace App\Http\Contracts\Financial;

use App\Http\Contracts\Core\PsInterface;

interface OfflinePaymentSettingServiceInterface extends PsInterface
{
    public function save($offlinePaymentSettingData, $offlinePaymentSettingImage);

    public function update($id, $offlinePaymentSettingData);

    public function delete($id);

    public function get($id, $relation = null);

    public function setStatus($id, $status);
}
