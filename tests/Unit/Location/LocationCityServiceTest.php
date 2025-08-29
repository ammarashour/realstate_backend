<?php

namespace Tests\Unit\LocationCity;

use App\Helpers\PsTestHelper;
use App\Http\Contracts\Location\LocationCityInfoServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Http\Requests\StoreLocationCityRequest;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use app\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use app\Http\Contracts\Utilities\CustomFieldServiceInterface;
use Modules\Core\Http\Services\Configuration\BackendSettingService;
use Modules\Core\Http\Services\Location\LocationCityInfoService;
use Modules\Core\Http\Services\Location\locationCityService;
use Modules\Core\Http\Services\Utilities\CustomFieldService;
use Tests\TestCase;

class LocationCityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $locationCityService;

    protected $locationCityServiceOriginal;

    protected $locationCityInfoServiceInterface;

    protected $locationCityInfoService;

    protected $BackendSettingService;

    protected $coreFieldFilterSettingService;

    protected $customFieldService;

    protected $customizeUiService;

    protected $customizeUiDetailService;

    protected $locationCityServiceInterface;

    protected $storeLocationCityRequest;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->BackendSettingService = Mockery::mock(BackendSettingService::class);
        $this->locationCityInfoServiceInterface = Mockery::mock(LocationCityInfoServiceInterface::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldService::class);
        $this->customFieldService = Mockery::mock(CustomFieldService::class);
        $this->customizeUiService = Mockery::mock(CustomFieldServiceInterface::class);
        $this->customizeUiDetailService = Mockery::mock(CustomFieldAttributeServiceInterface::class);
        $this->locationCityInfoService = Mockery::mock(LocationCityInfoService::class);
        $this->storeLocationCityRequest = Mockery::mock(StoreLocationCityRequest::class);

        $this->locationCityService = Mockery::mock(LocationCityService::class, [
            $this->BackendSettingService,
            $this->locationCityInfoServiceInterface,
        ])->makePartial();

        $this->locationCityServiceOriginal = new LocationCityService(
            $this->BackendSettingService,
            $this->locationCityInfoServiceInterface,
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->locationCityServiceOriginal);
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

        $cityData = [
            'name' => 'TestCity1',
            'description' => 'desc',
            'lat' => 56.3,
            'lng' => 67.4,
            'city_relation' => [
                'loc00001' => 'relation1',
                'loc00002' => 'relation2',
            ],
        ];

        $relationalData = [
            'loc00001' => 'relation1',
            'loc00001' => 'relation2',
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        // For Success Case
        $this->locationCityInfoServiceInterface->shouldReceive('save')
            ->once()
            ->with(\Mockery::type('int'), $relationalData);

        $this->locationCityService->save($cityData, $relationalData);

        $city = $this->locationCityService->get(1);

        $this->assertEquals($cityData['name'], $city->name);

        // For Exception Case
        $errorMessage = 'Error Message!';

        $this->locationCityInfoServiceInterface->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->locationCityService->save($cityData, $relationalData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);
        $city = LocationCity::factory()->create();

        $cityData = [
            'name' => 'New Name',
            'description' => 'desc',
            'lat' => 67.88,
            'lng' => 55.78,
        ];

        // Since we are using mock,
        // it can be dummy string instead of file
        $relationalData = [
            'loc00001' => 'relation1',
            'loc00001' => 'relation2',
        ];
        // For Success Case
        $this->locationCityInfoServiceInterface->shouldReceive('update')
            ->once()
            ->with($city->id, $relationalData);

        $this->locationCityService->update($city->id, $cityData, $relationalData);

        $city = $this->locationCityService->get($city->id);

        $this->assertEquals($cityData['name'], $city->name);

        // For Exception Case
        $errorMessage = 'Error Message!';

        $this->locationCityInfoServiceInterface->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->locationCityService->update($city->id, $cityData, $relationalData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $city = LocationCity::factory()->create();

        $cityRelations = ['cus00001' => 'Email'];

        $this->locationCityInfoServiceInterface->shouldReceive('getAll')
            ->once()
            ->with(null, $city->id, null, 1, null)
            ->andReturn($cityRelations);

        $this->locationCityInfoServiceInterface->shouldReceive('deleteAll')
            ->once()
            ->with($cityRelations);

        $result = $this->locationCityService->delete($city->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $city = LocationCity::factory()->create();

        $result = $this->locationCityService->get($city->id);

        $this->assertNotNull($result);
        $this->assertEquals($city->id, $result->id);
        $this->assertEquals($city->name, $result->name);
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a city for testing
        $city = LocationCity::factory()->create(['status' => Constants::publish]);

        // Call the setStatus method
        $result = $this->locationCityService->setStatus($city->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(LocationCity::class, $result);
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

    public function test_saveCity()
    {

        $cityData = [
            'name' => 'Test1',
            'description' => 'desc',
            'lat' => 60.55,
            'lng' => 79.33,
            'city_relation' => [
                'relation1',
                'relation2',
            ],
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveCity', [$cityData]);

        $this->assertNotNull($result);
        $this->assertEquals($cityData['name'], $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateCity()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a blog for testing
        $ctiy = LocationCity::factory()->create(['status' => Constants::publish]);

        $ctiyData = [
            'name' => 'New test',
            'description' => 'desc',
            'lat' => 60.55,
            'lng' => 79.33,
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateCity', [$ctiy->id, $ctiyData]);
        $this->assertNotNull($result);
        $this->assertEquals($ctiyData['name'], $result->name);
        $this->assertEquals($ctiy->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteCity()
    {
        // Create a blog for testing
        $city = LocationCity::factory()->create(['status' => Constants::publish]);

        $cityName = $this->psTestHelper->invokePrivateMethod('deleteCity', [$city->id]);
        $this->assertEquals($city->name, $cityName);

        $result = $this->locationCityService->get($city->id);
        $this->assertNull($result);
    }
}
