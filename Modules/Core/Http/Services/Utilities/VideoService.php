<?php

namespace Modules\Core\Http\Services\Utilities;

use App\Config\Cache\ItemCache;
use App\Http\Services\PsService;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Image\ImageProcessingServiceInterface;
use App\Http\Contracts\Utilities\VideoServiceInterface;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\File;
use Modules\Core\Entities\Utilities\VideoChunk;
use Modules\Core\Entities\Utilities\VideoUpload;
use Modules\Core\Http\Facades\PsCache;

class VideoService extends PsService implements VideoServiceInterface
{
    private $storage_upload_path = '/storage/'.Constants::folderPath.'/uploads/';

    public function __construct(protected ImageProcessingServiceInterface $imageProcessingService) {}

    public function uploadVideo($userId, $file, $meta, $data)
    {

        DB::beginTransaction();
        try {
            
            // Prepare Storage for video upload
            $tmpStorageDir = $this->prepareStorage($userId, $meta['name']);

            // Handling meta data
            $videoUploadRecords = $this->handleMetaDatabase($userId, $meta);

            // Prepare File            
            $fileName = $this->getOffsetFileName($meta['name'], $meta['chunk_no']);

            // Save chunk in temp directory
            $chunkPath = $tmpStorageDir . "/" . $fileName;        
            file_put_contents($chunkPath, file_get_contents($file->getRealPath()));
            
            // Save Chunk Data
            $videoUploadRecords = $this->saveVideoChunk($meta['name'], $meta['chunk_no'], $userId, $videoUploadRecords);

            // Check if all chunks have been uploaded
            if ($this->isAllChunksUploaded($videoUploadRecords, $meta)) {
                
                $this->updateVideoUploadStatus($meta['name'], 'complete');

                // Merge chunks
                $finalFilePath = $this->mergeChunks($tmpStorageDir, $meta['name'], $meta['total_chunks']);
                $extension = $file->getClientOriginalExtension();

                // Save or Update Video
                if (empty($meta['img_id'])) {                        
                    $this->saveVideo($finalFilePath, $extension, $data, $userId);
                }else {                
                    $this->updateVideo($meta, $finalFilePath, $extension, $data, $userId);
                }

                $this->clearTempRecords($tmpStorageDir, $meta);

            }

            DB::commit();

        } catch(Throwable $e) {

            DB::rollBack();
            throw $e;
        }

    }
    
    public function mergeChunks($storagePath, $uniqueFileName, $totalChunks)
    {
        $finalFilePath = $storagePath . '/' . $uniqueFileName;

        // Open the final file in append mode
        $finalFile = fopen($finalFilePath, 'wb');

        for($index = 1; $index <= $totalChunks; $index++) {
            $chunkPath = $storagePath . "/" . $this->getOffsetFileName($uniqueFileName, $index);
            if (file_exists($chunkPath)) {
                fwrite($finalFile, file_get_contents($chunkPath));
                unlink($chunkPath); // Delete chunk after merging
            }
        }
        fclose($finalFile);

        return $finalFilePath;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function clearTempRecords($tmpStorageDir, $meta){
        rmdir($tmpStorageDir);

        // remove videoUpload by name and videoChunks by name
        VideoUpload::where('file_name', $meta['name'])->delete();
        VideoChunk::where('file_name', $meta['name'])->delete();
    }
    
    private function saveVideo($finalFilePath, $extension, $data, $userId) 
    {
        // save video file
        $fileName = $this->getFinalFileName($extension);
        File::move($finalFilePath, public_path() . $this->storage_upload_path . $fileName); 

        // save video data at core_images table
        $video = new CoreImage();
        $video->fill($data);
        $video->img_path = $fileName;
        $video->added_user_id = $userId;
        $video->save();

        PsCache::clear(ItemCache::BASE);
    }

    private function updateVideo($meta, $finalFilePath, $extension, $data, $userId) 
    {
        $video = CoreImage::where(['id' => $meta['img_id']])->orderBy('id', 'desc')->first();
                
        if(!empty($video)){
            $this->imageProcessingService->deleteImageFile($video->img_path);
        }

        // save video file
        $fileName = $this->getFinalFileName($extension);
        File::move($finalFilePath, public_path() . $this->storage_upload_path . $fileName); 

        // update video data at core_images table
        $video->fill($data);
        $video->img_path = $fileName;
        $video->updated_user_id = $userId;
        $video->update();

        PsCache::clear(ItemCache::BASE);
    }

    private function isAllChunksUploaded($videoUploadRecords, $meta)
    {
        return count($videoUploadRecords['uploaded_chunks']) == $meta['total_chunks'];
    }

    private function getOffsetFileName($fileName, $offset) {

        return $offset . '_' . $fileName;
    }

    private function getFinalFileName($extension) {
        return uniqid() . '.' . $extension;
    }

    private function prepareStorage($userId, $fileName)
    {
        $storagePath = storage_path('app/uploads/videos/' . $userId . '/'. $fileName);
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0777, true, true);
        }

        return $storagePath;
    }

    private function updateVideoUploadStatus($fileName, $status)
    {
        return VideoUpload::where('file_name', $fileName)
            ->update(['status' => $status]);
    }

    private function saveVideoChunk($fileName, $offset, $userId, $metaData)
    {
        return DB::transaction(function () use ($fileName, $offset, $userId, $metaData) {
            // Check if the chunk already exists
            $exists = VideoChunk::where('file_name', $fileName)
                ->where('offset', $offset)
                ->exists();

            if ($exists) {
                return $metaData;
            }

            // Insert the chunk record
            VideoChunk::create([
                'file_name' => $fileName,
                'offset' => $offset,
                'added_user_id' => $userId
            ]);

            // Update the metadata
            $metaData['uploaded_chunks'][] = $offset;

            return $metaData;
        });
    }
    private function handleMetaDatabase($userId, $meta) {
        $fileName = $meta['name'];
        // Use transaction to prevent race conditions
        return DB::transaction(function () use ($userId, $fileName, $meta) {
            // Try to find an existing video upload record
            $videoUpload = VideoUpload::where('file_name', $fileName)->first();
            
            if (!$videoUpload) {                
                // Create a new video upload record
                $videoUpload = VideoUpload::create([
                    'file_name' => $fileName,
                    'file_size' => $meta['file_size'],
                    'total_chunks' => $meta['total_chunks'],
                    'status' => 'in_progress',
                    'added_user_id' => $userId,
                ]);                
            }

            // Get all uploaded chunks for this file
            $uploadedChunks = VideoChunk::where('file_name', $fileName)
                ->orderBy('offset')
                ->pluck('offset')
                ->toArray();

            // Check if this chunk was already uploaded
            if (in_array($meta['chunk_no'], $uploadedChunks)) {
                throw new \Exception('Chunk already uploaded');
            }

            return [
                'file_name' => $videoUpload->file_name,
                'last_upload_time' => time(),
                'total_chunks' => $videoUpload->total_chunks,
                'fileSize' => $videoUpload->file_size,
                'uploaded_chunks' => $uploadedChunks,
                'status' => $videoUpload->status,
            ];
        });
    }

}