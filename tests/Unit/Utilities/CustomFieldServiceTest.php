<?php

namespace Tests\Unit\Utilities;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Http\Services\Utilities\CustomFieldService;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class CustomFieldServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $customFieldServiceOriginal;

    protected $psTestHelper;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customFieldServiceOriginal = new CustomFieldService();

        $this->psTestHelper = new PsTestHelper($this->customFieldServiceOriginal);

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
        $customFieldData = (object) [
            'name' => 'loc00001_name',
            'placeholder' => 'loc00001_placeholder',
            'ui_type_id' => 'uit00001',
            'core_keys_id' => 'loc00001',
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
            'is_core_field' => 0,
            'permission_for_enable_disable' => 0,
            'permission_for_delete' => 0,
            'permission_for_mandatory' => 0,
            'category_id' => null,
            'added_date' => '',
            'added_user_id' => 1,
            'updated_date' => '',
            'updated_user_id' => 1,
            'updated_flag' => '',
        ];

        // Call the method
        $customField = $this->customFieldServiceOriginal->save($customFieldData);

        assertNotNull($customField);
        assertEquals($customFieldData->name, $customField->name);
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $customField = CustomField::factory()->create();

        // Prepare Data
        $customFieldData = [
            'name' => 'loc00001_name',
        ];

        // Call the method
        $customField = $this->customFieldServiceOriginal->update($customField->id, $customFieldData);

        assertNotNull($customField);
        assertEquals($customFieldData['name'], $customField->name);
        assertEquals($this->user->id, $customField->updated_user_id);
    }

    public function test_deleteAll()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        CustomField::factory(3)->create();

        // Call the method
        $this->customFieldServiceOriginal->deleteAll(isByTruncate: Constants::yes);
        $customFields = $this->customFieldServiceOriginal->getAll(withNoPag: Constants::yes);

        $this->assertEquals([], $customFields->toArray());
    }

    public function test_delete()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $customField = CustomField::factory()->create();

        // Call the method
        $name = $this->customFieldServiceOriginal->delete($customField->id);
        $customField = $this->customFieldServiceOriginal->get(id: $customField->id);

        $this->assertEquals($customField->name, $name);
        $this->assertEquals(Constants::delete, $customField->is_delete);
    }

    public function test_get()
    {
        // prepare Data
        $customField = CustomField::factory()->create();

        // Call the method
        $successResult = $this->customFieldServiceOriginal->get($customField->id);

        assertEquals($customField->id, $successResult->id);
    }

    public function test_getAll()
    {
        // prepare Data
        $customizeUi = CustomField::factory(2)->create();

        // call the method
        $successResult = $this->customFieldServiceOriginal->getAll(withNoPag: Constants::yes);

        assertNotNull($successResult);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

}
