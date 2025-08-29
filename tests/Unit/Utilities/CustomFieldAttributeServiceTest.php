<?php

namespace Tests\Unit\Utilities;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Http\Services\Utilities\CustomFieldAttributeService;
use Tests\TestCase;

class CustomFieldAttributeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $customFieldAttributeService;

    protected $customFieldAttributeServiceOriginal;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customFieldAttributeService = Mockery::mock(CustomFieldAttributeService::class);

        $this->customFieldAttributeServiceOriginal = new CustomFieldAttributeService();

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->customFieldAttributeServiceOriginal);

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

        // Prepare Data For Fail
        $customFieldAttributeData = [
            'name' => 'Test1',
            'core_keys_id' => 'loc00001',
        ];

        // For Success Case
        $this->customFieldAttributeServiceOriginal->save($customFieldAttributeData);

        $customFieldAttribute = $this->customFieldAttributeServiceOriginal->get(1);
        $this->assertEquals($customFieldAttribute['name'], $customFieldAttribute->name);

        // Prepare Data For Fail
        $customFieldAttributeData = [
            'name' => 'Test1',
        ];

        // For Exception Case
        $this->expectException(\Exception::class);
        $this->customFieldAttributeServiceOriginal->save($customFieldAttributeData);
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $customFieldAttribute = CustomFieldAttribute::factory()->create();

        // Prepare Data For Fail
        $customFieldAttributeData = [
            'name' => 'Test1',
            'core_keys_id' => 'loc00001',
        ];

        // For Success Case
        $this->customFieldAttributeServiceOriginal->update($customFieldAttribute->id, $customFieldAttributeData);

        $customFieldAttribute = $this->customFieldAttributeServiceOriginal->get($customFieldAttribute->id);
        $this->assertEquals($customFieldAttributeData['name'], $customFieldAttribute->name);

        // Prepare Data For Fail
        $customFieldAttributeData = [
            'name' => null,
        ];

        // For Exception Case
        $this->expectException(\Exception::class);
        $this->customFieldAttributeServiceOriginal->update($customFieldAttribute->id, $customFieldAttributeData);
    }

    public function test_delete()
    {

        $customFieldAttribute = CustomFieldAttribute::factory()->create();

        $result = $this->customFieldAttributeServiceOriginal->delete($customFieldAttribute->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);

    }

    public function test_get()
    {

        $customFieldAttribute = CustomFieldAttribute::factory()->create();

        $result = $this->customFieldAttributeServiceOriginal->get($customFieldAttribute->id);

        $this->assertNotNull($result);
        $this->assertEquals($customFieldAttribute->id, $result->id);
        $this->assertEquals($customFieldAttribute->name, $result->name);

    }

    // ////////////////////////////////////////////////////////////////////
    // /// Private Function Test Cases
    // ////////////////////////////////////////////////////////////////////

    public function test_saveCustomFieldAttribute()
    {
        $customFieldAttributeData = [
            'name' => 'Test1',
            'core_keys_id' => 'loc00001',
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveCustomFieldAttribute', [$customFieldAttributeData]);

        $this->assertNotNull($result);
        $this->assertEquals($customFieldAttributeData['name'], $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);

    }

    public function test_updateCustomFieldAttribute()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a blog for testing
        $customFieldAttribute = CustomFieldAttribute::factory()->create();

        $customFieldAttributeData = [
            'name' => 'Test1',
            'core_keys_id' => 'loc00001',
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateCustomFieldAttribute', [$customFieldAttribute->id, $customFieldAttributeData]);
        $this->assertNotNull($result);
        $this->assertEquals($customFieldAttributeData['name'], $result->name);
        $this->assertEquals($customFieldAttribute->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);

    }

    public function test_deleteCustomFieldAttribute()
    {
        // Create a CustomFieldAttribute for testing
        $customFieldAttribute = CustomFieldAttribute::factory()->create();

        $customFieldAttributeName = $this->psTestHelper->invokePrivateMethod('deleteCustomFieldAttribute', [$customFieldAttribute->id]);
        $this->assertEquals($customFieldAttribute->name, $customFieldAttributeName);

        $result = $this->customFieldAttributeServiceOriginal->get($customFieldAttribute->id);
        $this->assertNull($result);

    }
}
