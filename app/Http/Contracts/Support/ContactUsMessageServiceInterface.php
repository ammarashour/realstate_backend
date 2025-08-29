<?php

namespace App\Http\Contracts\Support;

use App\Http\Contracts\Core\PsInterface;

interface ContactUsMessageServiceInterface extends PsInterface
{
    public function save($contactUsMsgData);

    public function update($id, $contactUsMsgData);

    public function get($relation = null, $id = null);

    public function getAll($relation = null, $conds = null, $limit = null, $offset = null);

    public function markAllAsRead();

    public function multiDelete($ids);

    public function delete($id);

    public function csvExport();
}