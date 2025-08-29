<?php

namespace App\Http\Contracts\User;

use App\Http\Contracts\Core\PsInterface;

interface UserServiceInterface extends PsInterface
{
    public function save($userData, $userCoverPhoto, $relationalData = []);

    public function update($id, $userData, $userCoverPhoto, $relationalData = []);

    public function delete($id);

    public function get($id = null, $conds = null, $relation = null);

    public function getAll(
        $relation = null,
        $status = null,
        $isBanned = null,
        $conds = null,
        $limit = null,
        $offset = null,
        $condsIn = null,
        $noPagination = null,
        $pagPerPage = null,
        $sort = null,
        $report = null,
        $isTopRatedSeller = null
    );

    public function setStatus($id, $status);

    public function ban($id, $ban);

    public function replaceImage($id, $userCoverPhoto);

    public function deleteImage($id);

    public function reportCsvExport($reportName, $reportExportClass);

    public function addFreeAdPostCount($userId);
}
