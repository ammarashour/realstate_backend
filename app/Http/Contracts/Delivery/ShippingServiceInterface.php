<?php

namespace App\Http\Contracts\Delivery;

use App\Http\Contracts\Core\PsInterface;

interface ShippingServiceInterface extends PsInterface
{
    public function save($shippingData);

    public function update($id, $shippingData);

    public function getAll($relation = null);

    public function get($id = null);

    public function delete($id);

    public function setStatus($id, $status);
}