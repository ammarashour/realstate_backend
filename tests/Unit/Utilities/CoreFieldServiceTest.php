<?php

namespace Tests\Unit\Utilities;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class CoreFieldServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $coreFieldServiceOriginal;

    protected $psTestHelper;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coreFieldServiceOriginal = new CoreFieldService();

        $this->psTestHelper = new PsTestHelper($this->coreFieldServiceOriginal);

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
        $coreFieldData = (object) [
            'placeholder' => 'loc00001_placeholder',
            'label_name' => 'loc00001',
            'field_name' => 'id',
            'mandatory' => 0,
            'is_show_sorting' => 1,
            'is_show_in_filter' => 1,
            'ordering' => 1,
            'enable' => 1,
            'is_delete' => 0,
            'module_name' => 'loc',
            'data_type' => 'String',
            'table_id' => 1,
            'project_id' => 1,
            'project_name' => 'Testing',
            'base_module_name' => 16,
            'is_include_in_hideshow' => 1,
            'is_show' => 1,
            'is_core_field' => 1,
            'permission_for_enable_disable' => 0,
            'permission_for_delete' => 0,
            'permission_for_mandatory' => 0,
            'added_date' => '',
            'added_user_id' => 1,
            'updated_date' => '',
            'updated_user_id' => 1,
            'updated_flag' => '',
        ];

        // Call the method
        $coreField = $this->coreFieldServiceOriginal->save($coreFieldData);

        assertNotNull($coreField);
        assertEquals($coreFieldData->label_name, $coreField->label_name);
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $customField = CoreField::factory()->create();

        // Prepare Data
        $customFieldData = [
            'label_name' => 'testing',
        ];

        // Call the method
        $customField = $this->coreFieldServiceOriginal->update($customField->id, $customFieldData);

        assertNotNull($customField);
        assertEquals($customFieldData['label_name'], $customField->label_name);
        assertEquals($this->user->id, $customField->updated_user_id);
    }

    public function test_deleteAll()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        CoreField::factory(3)->create();

        // Call the method
        $this->coreFieldServiceOriginal->deleteAll(isByTruncate: Constants::yes);
        $coreFields = $this->coreFieldServiceOriginal->getAll(withNoPag: Constants::yes);

        $this->assertEquals([], $coreFields->toArray());
    }

    public function test_delete()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $coreField = CoreField::factory()->create();

        // Call the method
        $name = $this->coreFieldServiceOriginal->delete($coreField->id);
        $coreField = $this->coreFieldServiceOriginal->get(id: $coreField->id);

        $this->assertEquals($coreField->name, $name);
        $this->assertEquals(Constants::delete, $coreField->is_delete);
    }

    public function test_get()
    {
        // prepare Data
        $coreField = CoreField::factory()->create();

        // Call the method
        $successResult = $this->coreFieldServiceOriginal->get($coreField->id);

        assertEquals($coreField->id, $successResult->id);
    }

    public function test_getAll()
    {
        // prepare Data
        $customizeUi = CoreField::factory(2)->create();

        // call the method
        $successResult = $this->coreFieldServiceOriginal->getAll(withNoPag: Constants::yes);

        assertNotNull($successResult);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

}
