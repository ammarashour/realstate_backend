<?php

namespace Tests\Unit\Category;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\PsTestHelper;
use Modules\Core\Entities\Role;
use Illuminate\Http\UploadedFile;
use Modules\Core\Entities\Category\Category;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Http\Requests\Category\storeSubcategoryRequest;
use Modules\Core\Http\Services\Category\CategoryService;
use App\Http\Contracts\Category\CategoryServiceInterface;
use Modules\Core\Http\Services\Category\SubcategoryService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Transformers\Backend\Model\Category\CategoryWithKeyResource;
use Modules\Core\Http\Controllers\Backend\Controllers\Category\SubcategoryController;
use Modules\Core\Http\Requests\Category\UpdateSubcategoryRequest;
use Modules\Core\Http\Services\Localization\LanguageService;

class SubcategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $subcategoryService;
    protected $categoryService;
    protected $coreFieldFilterSettingService;
    protected $languageService;
    protected $storeSubcategoryRequest;
    protected $updateSubcategoryRequest;
    protected $request;
    protected $subcategoryController;
    protected $subcategoryControllerOriginal;
    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->subcategoryService = Mockery::mock(SubcategoryService::class);
        $this->categoryService = Mockery::mock(CategoryService::class);
        $this->languageService = Mockery::mock(LanguageService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);

        // Mock StoreBlogRequest
        $this->storeSubcategoryRequest = Mockery::mock(StoreSubcategoryRequest::class);
        $this->updateSubcategoryRequest = Mockery::mock(UpdateSubcategoryRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the SubcategoryController to mock the handlePermission method
        $this->subcategoryController = Mockery::mock(SubcategoryController::class, [
            $this->subcategoryService,
            $this->categoryService,
            $this->languageService,
            $this->coreFieldFilterSettingService,
        ])->makePartial();

        $this->subcategoryControllerOriginal = new SubcategoryController(
            $this->subcategoryService,
            $this->categoryService,
            $this->languageService,
            $this->coreFieldFilterSettingService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->subcategoryControllerOriginal);
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

        $this->storeSubcategoryRequest->shouldReceive('validated')->twice()->andReturn([
            'name' => 'Test Subcategory',
            'category_id' => '1',
            'status' => '1',
        ]);

        $this->storeSubcategoryRequest->shouldReceive('file')
            ->with('cover')
            ->andReturn($file);

        $this->storeSubcategoryRequest->shouldReceive('file')
            ->with('icon')
            ->andReturn($file);

        // Mock subcategoryService
        $this->subcategoryService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Subcategory Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->subcategoryController->store($this->storeSubcategoryRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->subcategoryService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->subcategoryController->store($this->storeSubcategoryRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        // Simulate a file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        // Mock storeSubcategoryRequest
        $this->updateSubcategoryRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'name' => 'Update Subcategory',
            'status' => 0,
        ]);

        $this->updateSubcategoryRequest->shouldReceive('file')
            ->with('cover') // Replace with your actual file input name/key
            ->andReturn($file);

        $this->updateSubcategoryRequest->shouldReceive('file')
            ->with('icon') // Replace with your actual file input name/key
            ->andReturn($file);

        $this->updateSubcategoryRequest->shouldReceive('input')
            ->with('cover_id')
            ->andReturn(123);

        $this->updateSubcategoryRequest->shouldReceive('input')
            ->with('icon_id')
            ->andReturn(123);

        // Mock subcategoryService
        $this->subcategoryService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Category Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->subcategoryController->update($this->updateSubcategoryRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->subcategoryService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->subcategoryController->update($this->updateSubcategoryRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a subcategory for testing
        $subcategory = Subcategory::factory()->create();

        // Mock CategoryService
        $this->subcategoryService->shouldReceive('get')->once()->with($subcategory->id)->andReturn($subcategory);

        // Ensure handlePermission does nothing
        $this->subcategoryController->shouldReceive('handlePermissionWithModel')
            ->with($subcategory, Constants::deleteAbility);

        $this->subcategoryService->shouldReceive('delete')->once()->with($subcategory->id)->andReturn([
            'msg' => 'Subcategory deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->subcategoryController->destroy($subcategory->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Subcategory deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Subcategory Data
        $subcategory = Subcategory::factory()->create(['status' => Constants::publish]);

        // Mock the get method to return the subcategory instance
        $this->subcategoryService->shouldReceive('get')->once()
            ->andReturn($subcategory);

        // Ensure handlePermission does nothing
        $this->subcategoryController->shouldReceive('handlePermissionWithModel')
            ->with($subcategory, Constants::editAbility);

        // Mock the setStatus method
        $this->subcategoryService->shouldReceive('setStatus')->once()
            ->with($subcategory->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->subcategoryController->statusChange($subcategory->id);

        // Assert the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    public function test_importCSV()
    {
        $this->request->shouldReceive('file')
            ->with(Constants::csvFile);

        $response = $this->subcategoryController->importCSV($this->request);

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
            'category_filter' => 'all',
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'row' => 10
        ];

        foreach ($inputs as $key => $value) {
            $this->request->shouldReceive('input')
                ->with($key)
                ->andReturn($value);
        }

        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->once()
            ->with(null, $langConds)
            ->andReturn($language);

        $conds = [
            'searchterm' => $inputs['search'],
            'category_id' => $inputs['category_filter'] === 'all' ? null : $inputs['category_filter'],
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
        ];

        $relations = ['owner', 'editor', 'category.categoryLanguageString' => function ($q) use ($language) {
            $q->where('language_id', $language->id);
        }];
        $this->subcategoryService->shouldReceive('getAll')
            ->once()
            ->with(
                Mockery::on(function ($relations) use ($language) {
                    return is_array($relations) &&
                        isset($relations['category.categoryLanguageString']) &&
                        is_callable($relations['category.categoryLanguageString']);
                }),
                null,
                null,
                null,
                $conds,
                false,
                $inputs['row']
            )
            ->andReturn(collect());

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('subcategories', $result);
        $this->assertArrayHasKey('selectedCategory', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareCreateData()
    {
        $this->categoryService->shouldReceive('getAll')
            ->once()
            ->with(null, Constants::publish)
            ->andReturn(collect());

        // Invoke the private method
        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        // Assertions
        $this->assertArrayHasKey('categories', $result);
    }

    public function test_prepareEditData()
    {
        $id = 1;

        $dataWithRelation = ['category', 'cover', 'icon'];
        $this->subcategoryService->shouldReceive('get')
            ->once()
            ->with($id, null, $dataWithRelation)
            ->andReturn([]);

        $language = new \stdClass();
        $language->id = 1;

        $this->categoryService->shouldReceive('getAll')
            ->once()
            ->with(null, Constants::publish)
            ->andReturn(collect());

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('subcategory', $result);
        $this->assertArrayHasKey('categories', $result);
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

    public function test_prepareLanguageData()
    {
        $result = $this->psTestHelper->invokePrivateMethod('prepareLanguageData', []);

        $this->assertNotNull($result);
        $this->assertEquals('en', $result['symbol']);
    }
}
