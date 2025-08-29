<?php

namespace App\Http\Contracts\Utilities;

interface ChunkUpdateServiceInterface
{
    public function updateRecordsInChunks(array $updates, string $table, string $primaryKey = 'id', $batchSize = null);
}
