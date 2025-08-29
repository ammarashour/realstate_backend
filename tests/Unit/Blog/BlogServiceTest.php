<?php

namespace Tests\Unit\Blog;

use App\Helpers\PsTestHelper;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Http\Services\Information\BlogService;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Configuration\MobileSettingService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Location\LocationCityService;
use Tests\TestCase;

class BlogServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $blogService;

    protected $blogServiceOriginal;

    protected $imageService;

    protected $locationCityService;

    protected $coreFieldFilterSettingService;

    protected $mobileSettingService;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageService = Mockery::mock(ImageServiceInterface::class);
        $this->locationCityService = Mockery::mock(LocationCityService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);
        $this->mobileSettingService = Mockery::mock(MobileSettingService::class);

        $this->blogService = Mockery::mock(BlogService::class, [
            $this->imageService,
            $this->locationCityService,
            $this->coreFieldFilterSettingService,
            $this->mobileSettingService,
        ])->makePartial();

        $this->blogServiceOriginal = new BlogService(
            $this->imageService,
            $this->locationCityService,
            $this->coreFieldFilterSettingService,
            $this->mobileSettingService
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->blogServiceOriginal);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up Mockery
        Mockery::close();
    }

    ////////////////////////////////////////////////////////////////////
    /// Public Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_save()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $blogData = [
            'name' => 'Test1',
            'location_city_id' => 1,
            'description' => 'desc',
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        $blogImage = 'Image-File';
        $imgData = ['img_parent_id' => 1, 'img_type' => 'blog'];

        // For Success Case
        $this->imageService->shouldReceive('save')
            ->once()
            ->with($blogImage, $imgData);

        $this->blogService->save($blogData, $blogImage);

        $blog = $this->blogService->get(1);
        $this->assertEquals($blogData['name'], $blog->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->imageService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->blogService->save($blogData, $blogImage);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);
        $blog = Blog::factory()->create();

        $blogData = [
            'name' => 'New Name',
            'location_city_id' => 1,
            'description' => 'desc',
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        $blogImage = 'Image-File';
        $blogImageId = 1;
        $imgData = ['img_parent_id' => $blog->id, 'img_type' => 'blog'];

        // For Success Case
        $this->imageService->shouldReceive('update')
            ->once()
            ->with($blogImageId, $blogImage, $imgData);

        $this->blogService->update($blog->id, $blogData, $blogImageId, $blogImage);

        $updatedBlog = $this->blogService->get($blog->id);
        $this->assertEquals($blogData['name'], $updatedBlog->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->imageService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->blogService->update($blog->id, $blogData, $blogImageId, $blogImage);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $blog = Blog::factory()->create();
        $this->imageService->shouldReceive('deleteAll')
            ->once()
            ->with($blog->id);

        $result = $this->blogService->delete($blog->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $blog = Blog::factory()->create();

        $result = $this->blogService->get($blog->id);

        $this->assertNotNull($result);
        $this->assertEquals($blog->id, $result->id);
        $this->assertEquals($blog->name, $result->name);
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a blog for testing
        $blog = Blog::factory()->create(['status' => Constants::publish]);

        // Call the setStatus method
        $result = $this->blogService->setStatus($blog->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(Blog::class, $result);
        $this->assertEquals(Constants::unPublish, $result->status);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareSaveImageData()
    {

        $id = 1;

        // Assert the expected result
        $expected = [
            'img_parent_id' => $id,
            'img_type' => Constants::blogCoverImgType,
        ];

        $result = $this->psTestHelper->invokePrivateMethod('prepareSaveImageData', [$id]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_prepareUpdateStausData()
    {
        $status = 1;

        // Assert the expected result
        $expected = ['status' => $status];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateStausData', [$status]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_saveBlog()
    {

        $blogData = [
            'name' => 'Test1',
            'location_city_id' => 1,
            'description' => 'desc',
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveBlog', [$blogData]);

        $this->assertNotNull($result);
        $this->assertEquals($blogData['name'], $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateBlog()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a blog for testing
        $blog = Blog::factory()->create(['status' => Constants::publish]);

        $blogData = [
            'name' => 'New Name',
            'location_city_id' => 1,
            'description' => 'desc',
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateBlog', [$blog->id, $blogData]);
        $this->assertNotNull($result);
        $this->assertEquals($blogData['name'], $result->name);
        $this->assertEquals($blog->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteBlog()
    {
        // Create a blog for testing
        $blog = Blog::factory()->create(['status' => Constants::publish]);

        $blogName = $this->psTestHelper->invokePrivateMethod('deleteBlog', [$blog->id]);
        $this->assertEquals($blog->name, $blogName);

        $result = $this->blogService->get($blog->id);
        $this->assertNull($result);
    }
}
