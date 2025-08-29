<?php

namespace Tests\Unit\LocationCity;

use App\Helpers\PsTestHelper;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Entities\Location\LocationTownship;
use Modules\Core\Http\Services\Configuration\BackendSettingService;
use Modules\Core\Http\Services\Location\LocationTownshipService;
use Tests\TestCase;

class LocationTownshipServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $locationTownshipService;

    protected $locationTownshipServiceOriginal;

    protected $locationCityServiceInterface;

    protected $locationCityInfoService;

    protected $BackendSettingService;

    protected $coreFieldFilterSettingService;

    protected $customFieldService;

    protected $customizeUiService;

    protected $customizeUiDetailService;

    protected $storeLocationCityRequest;

    protected $user;

    protected $city;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->BackendSettingService = Mockery::mock(BackendSettingService::class);
        $this->locationCityServiceInterface = Mockery::mock(LocationCityServiceInterface::class);

        $this->locationTownshipService = Mockery::mock(LocationTownshipService::class, [
            $this->BackendSettingService,
            $this->locationCityServiceInterface,
        ])->makePartial();

        $this->locationTownshipServiceOriginal = new LocationTownshipService(
            $this->BackendSettingService,
            $this->locationCityServiceInterface,
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);
        $this->city = LocationCity::factory()->create();

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->locationTownshipServiceOriginal);
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

        $townshipData = [
            'name' => 'TestTownship1',
            'location_city_id' => $this->city->id,
            'lat' => 56.3,
            'lng' => 67.4,
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        // For Success Case
        $this->locationTownshipService->save($townshipData);

        $township = $this->locationTownshipService->get(1);

        $this->assertEquals($townshipData['name'], $township->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->locationTownshipService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($errorMessage);

        $result = $this->locationTownshipService->save($townshipData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);
        $township = LocationTownship::factory()->create();

        $townshipData = [
            'name' => 'New Township',
            'location_city_id' => $this->city->id,
            'lat' => 67.88,
            'lng' => 55.78,
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        // For Success Case
        $this->locationTownshipService->update($township->id, $townshipData);

        $township = $this->locationTownshipService->get($township->id);

        $this->assertEquals($townshipData['name'], $township->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->locationTownshipService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($errorMessage);

        $result = $this->locationTownshipService->update($townshipData, $township->id);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $township = LocationTownship::factory()->create();

        $result = $this->locationTownshipService->delete($township->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $township = LocationTownship::factory()->create();

        $result = $this->locationTownshipService->get($township->id);

        $this->assertNotNull($result);
        $this->assertEquals($township->id, $result->id);
        $this->assertEquals($township->name, $result->name);
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a township for testing
        $township = LocationTownship::factory()->create(['status' => Constants::publish]);

        // Call the setStatus method
        $result = $this->locationTownshipService->setStatus($township->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(LocationTownship::class, $result);
        $this->assertEquals(Constants::unPublish, $result->status);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareUpdateStatusData()
    {
        $status = 1;

        // Assert the expected result
        $expected = ['status' => $status];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateStatusData', [$status]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_saveTownship()
    {

        $townshipData = [
            'name' => 'TestTownship1',
            'location_city_id' => $this->city->id,
            'lat' => 56.3,
            'lng' => 67.4,
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveTownship', [$townshipData]);

        $this->assertNotNull($result);
        $this->assertEquals($townshipData['name'], $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateTownship()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a township for testing
        $township = LocationTownship::factory()->create(['status' => Constants::publish]);

        $townshipData = [
            'name' => 'New Township',
            'location_city_id' => $this->city->id,
            'lat' => 67.88,
            'lng' => 55.78,
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateTownship', [$township->id, $townshipData]);
        $this->assertNotNull($result);
        $this->assertEquals($townshipData['name'], $result->name);
        $this->assertEquals($township->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteTownship()
    {
        // Create a township for testing
        $township = LocationTownship::factory()->create(['status' => Constants::publish]);

        $townshipName = $this->psTestHelper->invokePrivateMethod('deleteTownship', [$township->id]);
        $this->assertEquals($township->name, $townshipName);

        $result = $this->locationTownshipService->get($township->id);
        $this->assertNull($result);
    }
}
