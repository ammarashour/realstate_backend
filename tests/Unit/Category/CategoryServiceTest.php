<?php

namespace Tests\Unit\Category;

use Mockery;
use stdClass;
use Tests\TestCase;
use App\Models\User;
use App\Helpers\PsTestHelper;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Information\BlogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Contracts\Image\ImageServiceInterface;
use Modules\Core\Http\Services\Localization\BeLanguageStringService;
use Modules\Core\Http\Services\Category\CategoryService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use App\Http\Contracts\Configuration\CoreKeyCounterServiceInterface;
use Modules\Core\Http\Services\Localization\LanguageService;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryService;

    protected $categoryServiceOriginal;

    protected $imageService;

    protected $languageStringService;

    protected $languageService;

    protected $coreKeyCounterService;

    protected $psTestHelper;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageService = Mockery::mock(ImageServiceInterface::class);
        $this->languageStringService = Mockery::mock(BeLanguageStringService::class);
        $this->languageService = Mockery::mock(LanguageService::class);
        $this->coreKeyCounterService = Mockery::mock(CoreKeyCounterServiceInterface::class);

        $this->categoryService = Mockery::mock(CategoryService::class, [
            $this->imageService,
            $this->languageStringService,
            $this->languageService,
            $this->coreKeyCounterService,
        ])->makePartial();

        $this->categoryServiceOriginal = new CategoryService(
            $this->imageService,
            $this->languageStringService,
            $this->languageService,
            $this->coreKeyCounterService,
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->categoryServiceOriginal);
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

        $languageString = [
            'values' => [
                ['value' => 'Eng Language Category', 'language_id' => 1],
                ['value' => 'Eng Language Category', 'language_id' => 2],
                // Add more entries as needed
            ]
        ];

        $categoryData = [
            'name' => 'Test1',
            'status' => 1,
            'nameForm' => []
        ];

        $nameForm = new \stdClass();
        foreach ($languageString as $key => $value) {
            $nameForm->$key = $value;
        }

        // Since we are using mock,
        // it can be dummy string instead of file
        $categoryImage = 'Image-File';
        $coverData = ['img_parent_id' => 1, 'img_type' => 'category-cover'];
        $iconData = ['img_parent_id' => 1, 'img_type' => 'category-icon'];

        // For Success Case
        $this->imageService->shouldReceive('save')
            ->once()
            ->with($categoryImage, $coverData);

        $this->imageService->shouldReceive('save')
            ->once()
            ->with($categoryImage, $iconData);

        $this->coreKeyCounterService->shouldReceive('generate')
            ->twice()
            ->with(Constants::categoryLanguage)
            ->andReturn('ctg-lang00001');

        $this->languageStringService->shouldReceive('storeCategoryLanguageStrings')
            ->once()
            ->with(Mockery::on(function ($nameForm) {
                return !is_array($nameForm);
            }), 'ctg-lang00001', 1);

        $this->categoryService->save($categoryData, $categoryImage, $categoryImage);

        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->once()
            ->with(null, $langConds)
            ->andReturn($language);

        $category = $this->categoryService->get(1);
        $this->assertNotNull($category);
        $this->assertEquals('ctg-lang00001', $category->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->imageService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->categoryService->save($categoryData, $categoryImage, $categoryImage);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $category = Category::factory()->create(['name' => 'ps-ctg00001']);

        $categoryData = [
            'name' => 'ps-ctg00001',
            'status' => 1,
            'nameForm' => [
                'key' => '_name',
                'value' => [
                    [
                        "value" => "test",
                        "id" => null,
                        "language_id" => "1",
                        "symbol" => "en"
                    ]
                ]
            ]
        ];

        $nameForm = new \stdClass();
        $nameForm->key = '_name';
        $nameForm->value = $categoryData['nameForm']['value'];

        // Since we are using mock,
        // it can be dummy string instead of file
        $categoryImage = 'Image-File';
        $categoryImageId = 1;
        $categoryIconId = 2;
        $imgData = ['img_parent_id' => $category->id, 'img_type' => 'category-cover'];
        $iconData = ['img_parent_id' => $category->id, 'img_type' => 'category-icon'];

        // For Success Case
        $this->imageService->shouldReceive('update')
            ->once()
            ->with($categoryImageId, $categoryImage, $imgData);

        $this->imageService->shouldReceive('update')
            ->once()
            ->with($categoryIconId, $categoryImage, $iconData);

        $this->languageStringService->shouldReceive('storeCategoryLanguageStrings')
            ->once()
            ->with(Mockery::on(function ($arg) use ($nameForm) {
                return $arg == $nameForm;
            }), $category->name, $category->id);

        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->twice()
            ->with(null, $langConds)
            ->andReturn($language);

        $this->categoryService->update($category->id, $categoryData, $categoryImageId, $categoryImage, $categoryIconId, $categoryImage);

        $updatedCategory = $this->categoryService->get($category->id);
        $this->assertEquals($category->name, $updatedCategory->name); // since it's lang key and not change

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->imageService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $this->languageService->shouldReceive('get')
            ->once()
            ->with(null, $langConds)
            ->andReturn($language);

        $result = $this->categoryService->update($category->id, $categoryData, $categoryImageId, $categoryImage, $categoryIconId, $categoryImage);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $category = Category::factory()->create();
        $this->imageService->shouldReceive('deleteAll')
            ->once()
            ->with($category->id);

        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->once()
            ->with(null, $langConds)
            ->andReturn($language);

        $result1 = $this->categoryService->delete($category->id);

        $this->assertNotNull($result1);
        $this->assertEquals('success', $result1['flag']);
        $this->assertArrayHasKey('msg', $result1);

        $result2 = $this->psTestHelper->invokePrivateMethod('delectCategoryLanguages', [$category->id]);
        $this->assertNull($result2);
    }

    public function test_get()
    {

        $category = Category::factory()->create();

        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->twice()
            ->with(null, $langConds)
            ->andReturn($language);

        $result = $this->categoryService->get($category->id);

        $this->assertNotNull($result);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($category->name, $result->name);

        // For Not Give Category id
        $result = $this->categoryService->get();

        $this->assertNotNull($result);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($category->name, $result->name);
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a blog for testing
        $category = Category::factory()->create(['status' => Constants::publish]);

        // Call the setStatus method
        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->once()
            ->with(null, $langConds)
            ->andReturn($language);

        $result = $this->categoryService->setStatus($category->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(Category::class, $result);
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
            'img_type' => Constants::categoryCoverImgType,
        ];

        $result = $this->psTestHelper->invokePrivateMethod('prepareSaveImageData', [$id]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_prepareSaveIconData()
    {

        $id = 1;

        // Assert the expected result
        $expected = [
            'img_parent_id' => $id,
            'img_type' => Constants::categoryIconImgType,
        ];

        $result = $this->psTestHelper->invokePrivateMethod('prepareSaveIconData', [$id]);

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

    public function test_saveCategory()
    {
        $categoryData = [
            'name' => 'Test1',
            'status' => '1',
        ];

        $this->coreKeyCounterService->shouldReceive('generate')
            ->once()
            ->with(Constants::categoryLanguage)
            ->andReturn('ctg-lang00001');

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveCategory', [$categoryData]);

        $this->assertNotNull($result);
        $this->assertEquals('ctg-lang00001', $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateCategory()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a category for testing
        $category = Category::factory()->create(['status' => Constants::publish]);

        $categoryData = [
            'name' => 'New Name',
            'status' => 0
        ];

        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->once()
            ->with(null, $langConds)
            ->andReturn($language);

        $result = $this->psTestHelper->invokePrivateMethod('updateCategory', [$category->id, $categoryData]);
        $this->assertNotNull($result);
        $this->assertEquals($categoryData['status'], $result->status);
        $this->assertEquals($category->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteCategory()
    {
        // Create a category for testing
        $category = Category::factory()->create(['status' => Constants::publish]);

        $language = new \stdClass();
        $language->id = 1;

        $langConds = ['symbol' => 'en'];
        $this->languageService->shouldReceive('get')
            ->twice()
            ->with(null, $langConds)
            ->andReturn($language);

        $categoryName = $this->psTestHelper->invokePrivateMethod('deleteCategory', [$category->id]);
        $this->assertEquals($category->name, $categoryName);

        $result = $this->categoryService->get($category->id);
        $this->assertNull($result);
    }

    public function test_generateCategoryLanguageString()
    {
        // Sample input data
        $languageString = [
            'values' => [
                ['value' => 'Eng Language Category', 'language_id' => 1],
                ['value' => 'Eng Language Category', 'language_id' => 2],
                // Add more entries as needed
            ]
        ];
        $categoryNameKey = 'category_name_key';
        $categoryId = 1;

        // Expected $nameForm object
        $nameForm = new \stdClass();
        foreach ($languageString as $key => $value) {
            $nameForm->$key = $value;
        }

        // Mock the BeLanguageStringService and set expectations
        $this->languageStringService->shouldReceive('storeCategoryLanguageStrings')
            ->once()
            ->with(Mockery::on(function ($arg) use ($nameForm) {
                return $arg == $nameForm;
            }), $categoryNameKey, $categoryId);

        // Call the private method
        $result = $this->psTestHelper->invokePrivateMethod('generateCategoryLanguageString', [
            $languageString,
            $categoryNameKey,
            $categoryId
        ]);

        // Assert result if needed, typically we just ensure no exceptions
        $this->assertNull($result);  // The method does not return anything, so we expect null
    }


    public function test_delectCategoryLanguages()
    {
        $id = 1;

        $result = $this->psTestHelper->invokePrivateMethod('delectCategoryLanguages', [$id]);

        $this->assertNull($result);
    }
}
