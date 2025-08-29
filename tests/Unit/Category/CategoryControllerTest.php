<?php

namespace Tests\Unit\Category;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Http\Controllers\Backend\Controllers\Category\CategoryController;
use Modules\Core\Http\Requests\StoreCategoryRequest;
use Modules\Core\Http\Requests\UpdateCategoryRequest;
use Modules\Core\Http\Services\Category\CategoryService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Localization\LanguageService;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryService;

    protected $coreFieldFilterSettingService;

    protected $languageService;

    protected $storeCategoryRequest;

    protected $updateCategoryRequest;

    protected $request;

    protected $categoryController;

    protected $categoryControllerOriginal;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->categoryService = Mockery::mock(CategoryService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);
        $this->languageService = Mockery::mock(LanguageService::class);

        // Mock StoreBlogRequest
        $this->storeCategoryRequest = Mockery::mock(StoreCategoryRequest::class);
        $this->updateCategoryRequest = Mockery::mock(UpdateCategoryRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the CategoryController to mock the handlePermission method
        $this->categoryController = Mockery::mock(CategoryController::class, [
            $this->categoryService,
            $this->coreFieldFilterSettingService,
            $this->languageService,
        ])->makePartial();

        $this->categoryControllerOriginal = new CategoryController(
            $this->categoryService,
            $this->coreFieldFilterSettingService,
            $this->languageService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->categoryControllerOriginal);
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

        $this->storeCategoryRequest->shouldReceive('validated')->twice()->andReturn([
            'name' => 'Test Category',
            'status' => '1',
        ]);

        $this->storeCategoryRequest->shouldReceive('file')
            ->with('cat_photo')
            ->andReturn($file);

        $this->storeCategoryRequest->shouldReceive('file')
            ->with('cat_icon')
            ->andReturn($file);

        // Mock blogService
        $this->categoryService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Category Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->categoryController->store($this->storeCategoryRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->categoryService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->categoryController->store($this->storeCategoryRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        // Simulate a file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        // Mock StoreCategoryRequest
        $this->updateCategoryRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'title' => 'Update Category',
            'status' => 0,
        ]);

        $this->updateCategoryRequest->shouldReceive('file')
            ->with('cat_photo') // Replace with your actual file input name/key
            ->andReturn($file);

        $this->updateCategoryRequest->shouldReceive('file')
            ->with('cat_icon') // Replace with your actual file input name/key
            ->andReturn($file);

        $this->updateCategoryRequest->shouldReceive('input')
            ->with('cover_id')
            ->andReturn(123);

        $this->updateCategoryRequest->shouldReceive('input')
            ->with('icon_id')
            ->andReturn(123);

        // Mock categoryService
        $this->categoryService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Category Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->categoryController->update($this->updateCategoryRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->categoryService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->categoryController->update($this->updateCategoryRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a category for testing
        $category = Category::factory()->create();

        // Mock CategoryService
        $this->categoryService->shouldReceive('get')->once()->with($category->id)->andReturn($category);

        // Ensure handlePermission does nothing
        $this->categoryController->shouldReceive('handlePermissionWithModel')
            ->with($category, Constants::deleteAbility);

        $this->categoryService->shouldReceive('delete')->once()->with($category->id)->andReturn([
            'msg' => 'Category deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->categoryController->destroy($category->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Category deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Category Data
        $category = Category::factory()->create(['status' => Constants::publish]);

        // Mock the get method to return the category instance
        $this->categoryService->shouldReceive('get')->once()
            ->andReturn($category);

        // Ensure handlePermission does nothing
        $this->categoryController->shouldReceive('handlePermissionWithModel')
            ->with($category, Constants::editAbility);

        // Mock the setStatus method
        $this->categoryService->shouldReceive('setStatus')->once()
            ->with($category->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->categoryController->statusChange($category->id);

        // Assert the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    public function test_importCSV()
    {
        $this->request->shouldReceive('file')
            ->with(Constants::csvFile);

        $response = $this->categoryController->importCSV($this->request);

        // // Assert the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareIndexData()
    {
        // Create a fake request with parameters
        $inputs = [
            'search' => 'keyword',
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
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
        ];

        $relations = ['owner', 'editor'];
        $this->categoryService->shouldReceive('getAll')
            ->once()
            ->with(
                $relations,
                null,
                null,
                null,
                null,
                $conds,
                false,
                $inputs['row']
            )
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('categories', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareEditData()
    {

        $id = 1;

        $dataWithRelation = ['cover', 'icon'];
        $this->categoryService->shouldReceive('get')
            ->once()
            ->with($id, $dataWithRelation)
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('category', $result);
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

    public function test_prepareStatusData()
    {
        $category = new \stdClass();
        $category->status = 0;

        $result = $this->psTestHelper->invokePrivateMethod('prepareStatusData', [$category]);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result);
    }
}
