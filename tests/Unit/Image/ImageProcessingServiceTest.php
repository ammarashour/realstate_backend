<?php

namespace Tests\Unit\Image;

use App\Http\Contracts\Image\WaterMarkServiceInterface;
use DateTimeImmutable;
use Illuminate\Http\UploadedFile;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Image\ImageProcessingService;
use Modules\Core\Http\Services\Image\ImageService;
use Tests\TestCase;

class ImageProcessingServiceTest extends TestCase
{
    protected ImageProcessingService $imageProcessingService;

    private $storage_upload_path = '/storage/'.Constants::folderPath.'/uploads/';

    private $storage_thumb1x_path = '/storage/'.Constants::folderPath.'/thumbnail/';

    private $storage_thumb2x_path = '/storage/'.Constants::folderPath.'/thumbnail2x/';

    private $storage_thumb3x_path = '/storage/'.Constants::folderPath.'/thumbnail3x/';

    protected function setUp(): void
    {
        parent::setUp();

        // Use dependency injection to instantiate the ImageService
        $this->imageProcessingService = new ImageProcessingService(
            app(WaterMarkServiceInterface::class)
        );
    }

    public function test_createIcoFile()
    {
        // Create a dummy file
        $file = UploadedFile::fake()->image('dummy.ico');
        $date = new DateTimeImmutable();
        $fileName = 'ico_test_'.$date->getTimestamp().'.ico';

        $this->imageProcessingService->createIcoFile($file, $fileName);
        $storage_upload_path = public_path().$this->storage_upload_path;
        $this->assertFileExists($storage_upload_path.$fileName);

        $storage_upload_path = public_path().$this->storage_thumb1x_path;
        $this->assertFileExists($storage_upload_path.$fileName);

        $storage_upload_path = public_path().$this->storage_thumb2x_path;
        $this->assertFileExists($storage_upload_path.$fileName);

        $storage_upload_path = public_path().$this->storage_thumb3x_path;
        $this->assertFileExists($storage_upload_path.$fileName);

        // Delete Files
        $this->imageProcessingService->deleteImageFile($fileName);

    }
}
