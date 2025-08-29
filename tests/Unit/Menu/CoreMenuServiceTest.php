<?php

namespace Tests\Unit\Menu;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Helpers\PsTestHelper;
use Modules\Core\Entities\Menu\Module;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\MobileSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Http\Services\Menu\ModuleService;
use Modules\Core\Http\Services\Menu\CoreMenuService;

class CoreMenuServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $coreMenuService;

    protected $moduleService;

    protected $coreMenuServiceOriginal;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->moduleService = Mockery::mock(ModuleService::class);

        $this->coreMenuService = Mockery::mock(CoreMenuService::class, [
            $this->moduleService,
        ])->makePartial();

        $this->coreMenuServiceOriginal = new CoreMenuService(
            $this->moduleService
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->coreMenuServiceOriginal);
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

        $coreMenuData = [
            'id' => 123,
            'module_name' => 'test_menu',
            'module_desc' => 'Test Menu',
            'module_lang_key' => 'test_menu_lang_key',
            'ordering' => '3',
            'is_show_on_menu' => '1',
            'module_id' => '9',
            'core_sub_menu_group_id' => '5',
        ];

        // For Success Case
        $moduleData = ['menu_id' => 123];
        $this->moduleService->shouldReceive('update')
            ->once()
            ->with($coreMenuData['module_id'], $moduleData);

        $this->coreMenuService->save($coreMenuData);

        $coreMenu = $this->coreMenuService->get(123);

        $this->assertEquals($coreMenu['module_name'], $coreMenu->module_name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreMenuService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->coreMenuService->save($coreMenu);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $coreMenu = CoreMenu::factory()->create(['id' => 123]);

        $coreMenuData = [
            'id' => 123,
            'module_name' => 'test_menu',
            'module_desc' => 'Test Menu',
            'module_lang_key' => 'test_menu_lang_key',
            'ordering' => '3',
            'is_show_on_menu' => '1',
            'module_id' => '10',
            'old_module_id' => '9',
            'core_sub_menu_group_id' => '5',
        ];

        // For Success Case
        $odlModuleData = ['menu_id' => 0];
        $this->moduleService->shouldReceive('update')
            ->once()
            ->with($coreMenuData['old_module_id'], $odlModuleData);

        $moduleData = ['menu_id' => 123];
        $this->moduleService->shouldReceive('update')
            ->once()
            ->with($coreMenuData['module_id'], $moduleData);

        $this->coreMenuService->update($coreMenu->id, $coreMenuData);

        $updatedModule = $this->coreMenuService->get($coreMenu->id);
        $this->assertEquals($coreMenuData['module_name'], $updatedModule->module_name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreMenuService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->coreMenuService->update($coreMenu->id, $coreMenuData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $coreMenu = CoreMenu::factory()->create();

        $moduleData = ['menu_id' => 0];

        $this->moduleService->shouldReceive('update')
            ->once()->with($coreMenu->module_id, $moduleData);

        $result = $this->coreMenuService->delete($coreMenu->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $coreMenu = CoreMenu::factory()->create();

        $result = $this->coreMenuService->get($coreMenu->id);

        $this->assertNotNull($result);
        $this->assertEquals($coreMenu->id, $result->id);
        $this->assertEquals($coreMenu->module_name, $result->module_name);
    }

    public function test_getAll()
    {
        MobileSetting::factory()->create();
        Project::factory()->create();
        $relation = ['owner', 'editor'];
        $row = 5;
        $result = $this->coreMenuService->getAll($relation, $row);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('owner', $result[0]);
        $this->assertEquals($row, $result->perPage());
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a coreMenu for testing
        $coreMenu = CoreMenu::factory()->create(['is_show_on_menu' => Constants::publish]);

        // Call the setStatus method
        $result = $this->coreMenuService->setStatus($coreMenu->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(CoreMenu::class, $result);
        $this->assertEquals(Constants::unPublish, $result->is_show_on_menu);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////
    public function test_prepareUpdateStausData()
    {
        $status = 1;

        // Assert the expected result
        $expected = ['is_show_on_menu' => $status];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateStausData', [$status]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_prepareUpdateModuleData()
    {
        $menuId = 1;

        // Assert the expected result
        $expected = ['menu_id' => $menuId];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateModuleData', [$menuId]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_saveModule()
    {

        $coreMenuData = [
            'module_name' => 'test_menu',
            'module_desc' => 'Test Menu',
            'module_lang_key' => 'test_menu_lang_key',
            'ordering' => '3',
            'is_show_on_menu' => '1',
            'module_id' => '9',
            'core_sub_menu_group_id' => '5',
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveCoreMenu', [$coreMenuData]);

        $this->assertNotNull($result);
        $this->assertEquals($coreMenuData['module_name'], $result->module_name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateModule()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a menu-group for testing
        $coreMenu = CoreMenu::factory()->create(['is_show_on_menu' => Constants::publish]);

        $coreMenuData = [
            'module_name' => 'test_menu',
            'module_desc' => 'Test Menu',
            'module_lang_key' => 'test_menu_lang_key',
            'ordering' => '3',
            'is_show_on_menu' => '0',
            'module_id' => '9',
            'core_sub_menu_group_id' => '5',
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateCoreMenu', [$coreMenu->id, $coreMenuData]);
        $this->assertNotNull($result);
        $this->assertEquals($coreMenuData['module_name'], $result->module_name);
        $this->assertEquals($coreMenu->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteModule()
    {
        // Create a Menu group for testing
        $coreMenu = CoreMenu::factory()->create();

        $coreMenuData = $this->psTestHelper->invokePrivateMethod('deleteCoreMenu', [$coreMenu->id]);
        $this->assertEquals($coreMenu->module_desc, $coreMenuData);

        $result = $this->coreMenuService->get($coreMenu->id);
        $this->assertNull($result);
    }
}
