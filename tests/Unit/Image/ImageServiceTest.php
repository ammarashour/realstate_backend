<?php

namespace Tests\Unit\Image;

use App\Http\Contracts\Image\ImageProcessingServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\BackendSetting;
use Modules\Core\Http\Services\Image\ImageService;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ImageService $imageService;

    protected function setUp(): void
    {
        parent::setUp();

        // Use dependency injection to instantiate the ImageService
        $this->imageService = new ImageService(
            app(ImageProcessingServiceInterface::class)
        );
    }

    /**
     * Save Image
     *
     * @return void
     */
    public function test_saveImage()
    {

        $backendSetting = new BackendSetting();
        $backendSetting->fill([
            'landscape_width' => 1000,
            'square_height' => 1000,
            'potrait_height' => 1000,
            'landscape_thumb_width' => 200,
            'square_thumb_height' => 200,
            'potrait_thumb_height' => 200,
            'landscape_thumb2x_width' => 300,
            'square_thumb2x_height' => 300,
            'potrait_thumb2x_height' => 300,
            'landscape_thumb3x_width' => 600,
            'square_thumb3x_height' => 600,
            'potrait_thumb3x_height' => 600,
            'watermask_color' => ' ',
            'added_user_id' => 1,
        ]);
        $backendSetting->save();

        // Create a user
        $user = User::factory()->create();

        $this->actingAs($user);

        // Create a dummy file
        $file = UploadedFile::fake()->image('dummy.jpg');

        $imgData = [
            'img_parent_id' => 1,
            'img_type' => Constants::blogCoverImgType,
        ];

        $savedImage = $this->imageService->save($file, $imgData);

        $retrievedImage = $this->imageService->get($imgData);

        $this->assertEquals($imgData['img_parent_id'], $retrievedImage->img_parent_id);
    }

    public function test_validateExtension()
    {
        $reflection = new \ReflectionClass($this->imageService);
        $method = $reflection->getMethod('validateExtension');
        $method->setAccessible(true);

        try {
            $result = $method->invokeArgs($this->imageService, ['jpg']);
            $this->assertNull($result); // Expect no exception, and no return value
        } catch (\Exception $e) {
            $this->fail('Exception should not have been thrown for a valid extension.');
        }

        try {
            $result = $method->invokeArgs($this->imageService, ['svg']);
            $this->assertNull($result); // Expect no exception, and no return value
        } catch (\Exception $e) {
            $this->fail('Exception should not have been thrown for a valid extension.');
        }

        try {
            $result = $method->invokeArgs($this->imageService, ['exe']);
            $this->fail('Expected InvalidFormatException was not thrown.');
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            $this->assertEquals('Invalid Format Extension.', $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception type: ' . get_class($e));
        }
    }
}
