<?php

namespace Tests\Unit\Utilities;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Services\Utilities\DynamicColumnVisibilityService;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class DynamicColumnVisibilityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $dynamicColumnVisibilityServiceOriginal;

    protected $psTestHelper;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dynamicColumnVisibilityServiceOriginal = new DynamicColumnVisibilityService();

        $this->psTestHelper = new PsTestHelper($this->dynamicColumnVisibilityServiceOriginal);

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

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

        // Prepare Data
        $dynamicColumnVisibilityData = (object) [
            'module_name' => 'loc',
            'key' => 'test',
            'is_show' => 1,
        ];

        // Call the method
        $dynamicColumnVisibility = $this->dynamicColumnVisibilityServiceOriginal->save($dynamicColumnVisibilityData);

        assertNotNull($dynamicColumnVisibility);
        assertEquals($dynamicColumnVisibilityData->module_name, $dynamicColumnVisibility->module_name);
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $dynamicColumnVisibility = DynamicColumnVisibility::factory()->create();

        // Prepare Data
        $dynamicColumnVisibilityData = [
            'module_name' => 'loc',
            'key' => 'test',
            'is_show' => 1,
        ];

        // Call the method
        $dynamicColumnVisibility = $this->dynamicColumnVisibilityServiceOriginal->update($dynamicColumnVisibility->id, $dynamicColumnVisibilityData);

        assertNotNull($dynamicColumnVisibility);
        assertEquals($dynamicColumnVisibilityData['module_name'], $dynamicColumnVisibility->module_name);
        assertEquals($this->user->id, $dynamicColumnVisibility->updated_user_id);
    }

    public function test_delete()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $DynamicColumnVisibilityByFactory = DynamicColumnVisibility::factory()->create();

        // Call the method
        $moduleName = $this->dynamicColumnVisibilityServiceOriginal->delete($DynamicColumnVisibilityByFactory->id);
        $DynamicColumnVisibilityAfterDel = $this->dynamicColumnVisibilityServiceOriginal->get(id: $DynamicColumnVisibilityByFactory->id);
        $this->assertEquals($DynamicColumnVisibilityByFactory->module_name, $moduleName);
        $this->assertNull($DynamicColumnVisibilityAfterDel);
    }

    public function test_get()
    {
        // prepare Data
        $DynamicColumnVisibility = DynamicColumnVisibility::factory()->create();

        // Call the method
        $successResult = $this->dynamicColumnVisibilityServiceOriginal->get($DynamicColumnVisibility->id);

        assertEquals($DynamicColumnVisibility->id, $successResult->id);
    }

    public function test_getAll()
    {
        // prepare Data
        $DynamicColumnVisibility = DynamicColumnVisibility::factory(2)->create();

        // call the method
        $successResult = $this->dynamicColumnVisibilityServiceOriginal->getAll(noPagination: Constants::yes);

        assertNotNull($successResult);
    }

    public function test_updateOrCreate()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $dynamicColumnVisibilityData = (object) [
            'module_name' => 'loc',
            'key' => 'test',
            'is_show' => 1,
        ];
        $dataArrWhere = [
            'module_name' => 'loc',
        ];

        // Call the method
        $dynamicColumnVisibility = $this->dynamicColumnVisibilityServiceOriginal->updateOrCreate($dataArrWhere, $dynamicColumnVisibilityData);

        $this->assertEquals(
            $dynamicColumnVisibilityData->module_name,
            $dynamicColumnVisibility->module_name
        );
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

}
