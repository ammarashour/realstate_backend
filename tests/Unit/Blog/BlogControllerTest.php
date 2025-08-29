<?php

namespace Tests\Unit\Blog;

use App\Helpers\PsTestHelper;
use App\Http\Contracts\Blog\BlogServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Mockery;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Http\Requests\Information\StoreBlogRequest;
use Modules\Core\Http\Requests\Information\UpdateBlogRequest;
use Modules\Core\Http\Services\Information\BlogService;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Controllers\Backend\Controllers\Information\BlogController;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Location\LocationCityService;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $blogController;

    protected $blogControllerOriginal;

    protected $blogService;

    protected $coreFieldFilterSettingService;

    protected $locationCityService;

    protected $storeBlogRequest;

    protected $updateBlogRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->blogService = Mockery::mock(BlogService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);
        $this->locationCityService = Mockery::mock(LocationCityService::class);

        // Mock StoreBlogRequest
        $this->storeBlogRequest = Mockery::mock(StoreBlogRequest::class);
        $this->updateBlogRequest = Mockery::mock(UpdateBlogRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the BlogController to mock the handlePermission method
        $this->blogController = Mockery::mock(BlogController::class, [
            $this->blogService,
            $this->coreFieldFilterSettingService,
            $this->locationCityService,
        ])->makePartial();

        $this->blogControllerOriginal = new BlogController(
            $this->blogService,
            $this->coreFieldFilterSettingService,
            $this->locationCityService
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->blogControllerOriginal);
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

    public function test_store()
    {
        // Simulate a file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        $this->storeBlogRequest->shouldReceive('validated')->twice()->andReturn([
            'title' => 'Test Blog',
            'description' => 'This is a test blog description.',
        ]);

        $this->storeBlogRequest->shouldReceive('file')
            ->with('cover')
            ->andReturn($file);

        // Mock blogService
        $this->blogService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->blogController->store($this->storeBlogRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->blogService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->blogController->store($this->storeBlogRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        // Simulate a file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        // Mock StoreBlogRequest
        $this->updateBlogRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'title' => 'Test Blog',
            'description' => 'This is a test blog description.',
        ]);

        $this->updateBlogRequest->shouldReceive('file')
            ->with('cover') // Replace with your actual file input name/key
            ->andReturn($file);

        $this->updateBlogRequest->shouldReceive('input')
            ->with('cover_id')
            ->andReturn(123);

        // Mock blogService
        $this->blogService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->blogController->update($this->updateBlogRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->blogService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->blogController->update($this->updateBlogRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a blog for testing
        $blog = Blog::factory()->create();

        // Mock BlogService
        $this->blogService->shouldReceive('get')->once()->with($blog->id)->andReturn($blog);

        // Ensure handlePermission does nothing
        $this->blogController->shouldReceive('handlePermissionWithModel')
            ->with($blog, Constants::deleteAbility);

        $this->blogService->shouldReceive('delete')->once()->with($blog->id)->andReturn([
            'msg' => 'Blog deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->blogController->destroy($blog->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Blog deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Blog Data
        $blog = Blog::factory()->create(['status' => Constants::publish]);

        // Mock the get method to return the blog instance
        $this->blogService->shouldReceive('get')->once()
            ->andReturn($blog);

        // Ensure handlePermission does nothing
        $this->blogController->shouldReceive('handlePermissionWithModel')
            ->with($blog, Constants::editAbility);

        // Mock the setStatus method
        $this->blogService->shouldReceive('setStatus')->once()
            ->with($blog->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->blogController->statusChange($blog->id);

        // Assert the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareCreateData()
    {

        $this->locationCityService->shouldReceive('getAll')
            ->once()->andReturn([]);

        $this->coreFieldFilterSettingService->shouldReceive('getCoreFields')
            ->once()->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('cities', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareIndexData()
    {
        // Create a fake request with parameters
        $inputs = [
            'search' => 'keyword',
            'city_filter' => 1,
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'row' => 10,
        ];

        foreach ($inputs as $key => $value) {
            $this->request->shouldReceive('input')
                ->with($key)
                ->andReturn($value);
        }

        $conds = [
            'searchterm' => $inputs['search'],
            'location_city_id' => $inputs['city_filter'],
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
        ];

        $relations = ['city', 'owner', 'editor'];
        $this->blogService->shouldReceive('getAll')
            ->once()
            ->with(
                $relations,
                null,
                null,
                null,
                false,
                $inputs['row'],
                $conds
            )
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('blogs', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareEditData()
    {

        $id = 1;

        $this->coreFieldFilterSettingService->shouldReceive('getCoreFields')
            ->once()
            ->with(
                null,
                null,
                null,
                1,
                null,
                null,
                null,
                null,
                null,
                Constants::blog
            )->andReturn([]);

        $dataWithRelation = ['cover', 'city'];
        $this->blogService->shouldReceive('get')
            ->once()
            ->with($id, $dataWithRelation)
            ->andReturn(null);

        $this->locationCityService->shouldReceive('getAll')
            ->once()
            ->with(null, Constants::publish)
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('blog', $result);
        $this->assertArrayHasKey('cities', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
        $this->assertArrayHasKey('validation', $result);
    }

    public function test_controlFieldArr()
    {

        $result = $this->psTestHelper->invokePrivateMethod('controlFieldArr', []);

        $this->assertNotNull($result);
        $this->assertEquals('core__be_action', $result[0]->label);
        $this->assertEquals('action', $result[0]->field);
        $this->assertEquals('Action', $result[0]->type);
        $this->assertEquals(false, $result[0]->sort);
        $this->assertEquals(0, $result[0]->ordering);
    }
}

/**
 * Just for reference,
 * in future it will delete
 */
// ** This Function is Just for Study Reference only
// ** We will not follow this style of code in actual unit test
// Only some specific using Mock
// public function test_statusChange_pertialMock()
// {
//     // Set Login User
//     $user = User::factory()->create(['role_id' => '1']);
//     $this->actingAs($user);

//     // Prepare Blog Data
//     $blog = Blog::factory()->create(['status' => 1]);

//     // Create a partial mock of the BlogController to mock the handlePermission method
//     $blogService = app(BlogServiceInterface::class);
//     $controller = $this->getMockBuilder(BlogController::class)
//         ->setConstructorArgs([
//             $blogService,
//             app(CoreFieldFilterSettingService::class),
//             app(LocationCityService::class),
//         ])
//         ->onlyMethods(['handlePermissionWithModel'])
//         ->getMock();

//     // Ensure handlePermission does nothing
//     $controller->method('handlePermissionWithModel');

//     // Cast to BlogController
//     $controller = (fn ($controller): BlogController => $controller)($controller);

//     // Call the statusChange method
//     $response = $controller->statusChange($blog->id, Constants::unPublish);

//     // Assert the response is a redirect
//     $this->assertInstanceOf(RedirectResponse::class, $response);
//     $this->assertEquals('The status has been updated successfully.', session('status')['msg']);

//     // Check the data is actually changed.
//     $updatedBlog = $blogService->get($blog->id);
//     $this->assertNotNull($updatedBlog);
//     $this->assertNotEquals($updatedBlog->status, $blog->status);

// }
