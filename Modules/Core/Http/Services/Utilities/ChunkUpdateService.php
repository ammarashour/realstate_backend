<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Config\ps_constant;
use App\Http\Contracts\Utilities\ChunkUpdateServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;

class ChunkUpdateService extends PsService implements ChunkUpdateServiceInterface
{
    public function updateRecordsInChunks(array $updates, string $table, string $primaryKey = 'id', $batchSize = null)
    {
        if(!$batchSize) {
            $batchSize = ps_constant::CHUNK_LIMIT;
        }

        if (empty($updates)) {
            return collect(); // Always return a collection to simplify merging later
        }

        $allUpdatedModels = collect();

        foreach (array_chunk($updates, $batchSize) as $batch) {
            $allUpdatedModels = $allUpdatedModels->merge(
                $this->updateChunkRecords($batch, $table, $primaryKey)
            );
        }

        return $allUpdatedModels;
    }

    /**
     * Process a single batch of updates for a given table and primary key.
     */
    private function updateChunkRecords(array $updates, string $table, string $primaryKey)
    {
        if (empty($updates)) {
            return collect();
        }

        $columns = array_keys($updates[0]);
        $setClauses = [];
        $identifiers = [];

        // Prepare CASE WHEN for each column
        foreach ($columns as $column) {
            if ($column === $primaryKey) {
                continue;
            }

            $caseWhen = [];
            foreach ($updates as $updateData) {
                $identifiers[] = $updateData[$primaryKey]; // Collect primary keys
                $value = addslashes($updateData[$column]); // Escape values
                $keyValue = addslashes($updateData[$primaryKey]);
                $caseWhen[] = "WHEN '$keyValue' THEN '$value'";
            }

            $setClauses[] = "$column = CASE $primaryKey " . implode(' ', $caseWhen) . " END";
        }

        $identifiers = array_unique($identifiers);
        $whereCondition = "'" . implode("', '", $identifiers) . "'";

        // Final SQL statement
        $sql = "UPDATE $table SET " . implode(', ', $setClauses) . " WHERE $primaryKey IN ($whereCondition)";
        DB::statement($sql);
    }
}
