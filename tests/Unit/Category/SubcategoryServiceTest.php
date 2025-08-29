<?php

namespace Tests\Unit\Category;

use App\Helpers\PsTestHelper;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Category\Subcategory;
use Modules\Core\Http\Services\Category\SubcategoryService;
use Tests\TestCase;

class SubcategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $subcategoryService;

    protected $subcategoryServiceOriginal;

    protected $imageService;

    protected $psTestHelper;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageService = Mockery::mock(ImageServiceInterface::class);

        $this->subcategoryService = Mockery::mock(SubcategoryService::class, [
            $this->imageService,
        ])->makePartial();

        $this->subcategoryServiceOriginal = new SubcategoryService(
            $this->imageService,
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->subcategoryServiceOriginal);
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

        $category = Category::factory()->create();
        $subcategoryData = [
            'name' => 'Test1',
            'category_id' => $category->id,
            'status' => 1,
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        $subcategoryImage = 'Image-File';
        $coverData = ['img_parent_id' => 1, 'img_type' => 'subCategory-cover'];
        $iconData = ['img_parent_id' => 1, 'img_type' => 'subCategory-icon'];

        // For Success Case
        $this->imageService->shouldReceive('save')
            ->once()
            ->with($subcategoryImage, $coverData);

        $this->imageService->shouldReceive('save')
            ->once()
            ->with($subcategoryImage, $iconData);

        $this->subcategoryService->save($subcategoryData, $subcategoryImage, $subcategoryImage);

        $subcategory = $this->subcategoryService->get(1);
        $this->assertNotNull($subcategory);
        $this->assertEquals($subcategoryData['name'], $subcategory->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->imageService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->subcategoryService->save($subcategoryData, $subcategoryImage, $subcategoryImage);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $subcategory = Subcategory::factory()->create();
        $category = Category::factory()->create();

        $subcategoryData = [
            'name' => 'New Name',
            'category_id' => $category->id,
            'status' => 1,
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        $subcategoryImage = 'Image-File';
        $subcategoryImageId = 1;
        $subcategoryIconId = 2;
        $imgData = ['img_parent_id' => $subcategory->id, 'img_type' => 'subCategory-cover'];
        $iconData = ['img_parent_id' => $subcategory->id, 'img_type' => 'subCategory-icon'];

        // For Success Case
        $this->imageService->shouldReceive('update')
            ->once()
            ->with($subcategoryImageId, $subcategoryImage, $imgData);

        $this->imageService->shouldReceive('update')
            ->once()
            ->with($subcategoryIconId, $subcategoryImage, $iconData);

        $this->subcategoryService->update($subcategory->id, $subcategoryData, $subcategoryImageId, $subcategoryImage, $subcategoryIconId, $subcategoryImage);

        $updatedCategory = $this->subcategoryService->get($subcategory->id);
        $this->assertEquals($subcategoryData['name'], $updatedCategory->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->imageService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->subcategoryService->update($subcategory->id, $subcategoryData, $subcategoryImageId, $subcategoryImage, $subcategoryIconId, $subcategoryImage);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $subcategory = Subcategory::factory()->create();
        $this->imageService->shouldReceive('deleteAll')
            ->once()
            ->with($subcategory->id);

        $result = $this->subcategoryService->delete($subcategory->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $subcategory = Subcategory::factory()->create();

        $result = $this->subcategoryService->get($subcategory->id);

        $this->assertNotNull($result);
        $this->assertEquals($subcategory->id, $result->id);
        $this->assertEquals($subcategory->name, $result->name);
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a subcategory for testing
        $subcategory = Subcategory::factory()->create(['status' => Constants::publish]);

        // Call the setStatus method
        $result = $this->subcategoryService->setStatus($subcategory->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(Subcategory::class, $result);
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
            'img_type' => Constants::subcategoryCoverImgType,
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
            'img_type' => Constants::subcategoryIconImgType,
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

    public function test_saveSubcategory()
    {
        $category = Category::factory()->create();
        $subcategoryData = [
            'name' => 'Test1',
            'category_id' => $category->id,
            'status' => '1',
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveSubcategory', [$subcategoryData]);

        $this->assertNotNull($result);
        $this->assertEquals('Test1', $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateSubcategory()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a subcategory for testing
        $subcategory = Subcategory::factory()->create(['status' => Constants::publish]);
        $category = Category::factory()->create();

        $subcategoryData = [
            'name' => 'New Name',
            'category_id' => $category->id,
            'status' => 0,
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateSubcategory', [$subcategory->id, $subcategoryData]);
        $this->assertNotNull($result);
        $this->assertEquals($subcategoryData['status'], $result->status);
        $this->assertEquals($subcategory->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteSubcategory()
    {
        // Create a subcategory for testing
        $subcategory = Subcategory::factory()->create(['status' => Constants::publish]);

        $subcategoryName = $this->psTestHelper->invokePrivateMethod('deleteSubcategory', [$subcategory->id]);
        $this->assertEquals($subcategory->name, $subcategoryName);

        $result = $this->subcategoryService->get($subcategory->id);
        $this->assertNull($result);
    }
}
