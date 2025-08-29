<?php

namespace Tests\Unit\Menu;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\MobileSetting;
use Modules\Core\Entities\Menu\Module;
use Modules\Core\Http\Services\Menu\ModuleService;
use Tests\TestCase;

class ModuleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $moduleService;

    protected $moduleServiceOriginal;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->moduleService = Mockery::mock(ModuleService::class)->makePartial();

        $this->moduleServiceOriginal = new ModuleService();

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->moduleServiceOriginal);
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

        $moduleData = [
            'id' => 123,
            'title' => 'Item Site Map',
            'lang_key' => 'item_site_map',
            'route_name' => 'itemSitemap',
            'is_not_from_sidebar' => '1',
            'status' => '0',
        ];

        // For Success Case
        $this->moduleService->save($moduleData);

        $module = $this->moduleService->get(123);

        $this->assertEquals($moduleData['title'], $module->title);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->moduleService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->moduleService->save($moduleData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $module = Module::factory()->create();

        $moduleData = [
            'title' => 'Item Site Map',
            'lang_key' => 'item_site_map',
            'route_name' => 'itemSitemap',
            'is_not_from_sidebar' => '1',
            'status' => '0',
        ];

        // For Success Case
        $this->moduleService->update($module->id, $moduleData);

        $updatedModule = $this->moduleService->get($module->id);
        $this->assertEquals($moduleData['title'], $updatedModule->title);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->moduleService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->moduleService->update($module->id, $moduleData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $module = Module::factory()->create();

        $result = $this->moduleService->delete($module->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $module = Module::factory()->create();

        $result = $this->moduleService->get($module->id);

        $this->assertNotNull($result);
        $this->assertEquals($module->id, $result->id);
        $this->assertEquals($module->title, $result->title);
    }

    public function test_getAll()
    {
        MobileSetting::factory()->create();
        $relation = ['owner', 'editor'];
        $row = 5;
        $result = $this->moduleService->getAll($relation, $row);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('owner', $result[0]);
        $this->assertEquals($row, $result->perPage());
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a module for testing
        $module = Module::factory()->create(['status' => Constants::publish]);

        // Call the setStatus method
        $result = $this->moduleService->setStatus($module->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(Module::class, $result);
        $this->assertEquals(Constants::unPublish, $result->status);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////
    public function test_prepareUpdateStausData()
    {
        $status = 1;

        // Assert the expected result
        $expected = ['status' => $status];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateStausData', [$status]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_saveModule()
    {

        $moduleData = [
            'title' => 'Item Site Map',
            'lang_key' => 'item_site_map',
            'route_name' => 'itemSitemap',
            'is_not_from_sidebar' => '0',
            'status' => '1',
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveModule', [$moduleData]);

        $this->assertNotNull($result);
        $this->assertEquals($moduleData['title'], $result->title);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateModule()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a menu-group for testing
        $module = Module::factory()->create(['status' => Constants::publish]);

        $moduleData = [
            'title' => 'Item Site Map',
            'lang_key' => 'item_site_map',
            'route_name' => 'itemSitemap',
            'is_not_from_sidebar' => '0',
            'status' => '1',
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateModule', [$module->id, $moduleData]);
        $this->assertNotNull($result);
        $this->assertEquals($moduleData['title'], $result->title);
        $this->assertEquals($module->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteModule()
    {
        // Create a Menu group for testing
        $module = Module::factory()->create(['status' => Constants::publish]);

        $moduleData = $this->psTestHelper->invokePrivateMethod('deleteModule', [$module->id]);
        $this->assertEquals($module->title, $moduleData);

        $result = $this->moduleService->get($module->id);
        $this->assertNull($result);
    }
}
