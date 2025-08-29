<?php

namespace App\Http\Contracts\Utilities;

use App\Http\Contracts\Core\PsInterface;

interface VideoServiceInterface extends PsInterface
{
    public function uploadVideo($userId, $file, $name, $data);

    // public function updateVideo($id, $file, $name, $data);
}
