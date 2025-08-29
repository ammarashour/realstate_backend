<?php

namespace Tests\Unit\AvailableCurrency;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

use Modules\Core\http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Tests\TestCase;

class AvailableCurrencyServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $availableCurrencyService;

    protected $availableCurrencyServiceOriginal;

    protected $coreFieldFilterSettingService;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {

        parent::setUp();

        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);

        $this->availableCurrencyService = Mockery::mock(AvailableCurrencyService::class, [
            $this->coreFieldFilterSettingService,
        ])->makePartial();

        $this->availableCurrencyServiceOriginal = new AvailableCurrencyService(
            $this->coreFieldFilterSettingService
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->availableCurrencyServiceOriginal);
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

        $testData = [
            'name' => 'Test1',
            'currency_symbol' => '$',
            'currency_short_form' => 'TCC',
        ];

        $this->availableCurrencyService->save($testData);
        $available_currency = $this->availableCurrencyService->get(1);
        $this->assertEquals($testData['name'], $available_currency->name);

        // // For Exception Case
        $errorMessage = 'Error Message!';
        $this->availableCurrencyService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->availableCurrencyService->save($testData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);
        $available_currency = AvailableCurrency::factory()->create();

        $testData = [
            'name' => 'Test1',
            'currency_symbol' => '$',
            'currency_short_form' => 'TCC',
        ];

        $this->availableCurrencyService->update($available_currency->id, $testData);

        $updatedData = $this->availableCurrencyService->get($available_currency->id);
        $this->assertEquals($testData['name'], $updatedData->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->availableCurrencyService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->availableCurrencyService->update($available_currency->id, $testData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $available_currency = AvailableCurrency::factory()->create();
        $result = $this->availableCurrencyService->delete($available_currency->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $available_currency = AvailableCurrency::factory()->create();

        $result = $this->availableCurrencyService->get($available_currency->id);

        $this->assertNotNull($result);
        $this->assertEquals($available_currency->id, $result->id);
        $this->assertEquals($available_currency->name, $result->name);
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

    public function test_saveAvailableCurrency()
    {

        $testData = [
            'name' => 'Test1',
            'currency_symbol' => '$',
            'currency_short_form' => 'TCC',
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveAvailableCurrency', [$testData]);

        $this->assertNotNull($result);
        $this->assertEquals($testData['name'], $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateAvailableCurrency()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a blog for testing
        $available_currency = AvailableCurrency::factory()->create(['status' => Constants::publish]);

        $testData = [
            'name' => 'Test1',
            'currency_symbol' => '$',
            'currency_short_form' => 'TCC',
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateAvailableCurrency', [$available_currency->id, $testData]);
        $this->assertNotNull($result);
        $this->assertEquals($testData['name'], $result->name);
        $this->assertEquals($available_currency->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteAvailableCurrency()
    {
        // Create a blog for testing
        $blog = AvailableCurrency::factory()->create(['status' => Constants::publish]);

        $blogName = $this->psTestHelper->invokePrivateMethod('deleteAvailableCurrency', [$blog->id]);
        $this->assertEquals($blog->name, $blogName);

        $result = $this->availableCurrencyService->get($blog->id);
        $this->assertNull($result);
    }
}
