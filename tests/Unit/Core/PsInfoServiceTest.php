<?php

namespace Tests\Unit\Core;

use App\Helpers\PsTestHelper;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Services\PsInfoService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Location\LocationCityInfo;
use Modules\Core\Entities\Utilities\CustomField;
use Tests\TestCase;

class PsInfoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $psInfoServiceOriginal;

    protected $user;

    protected $customFieldService;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->psInfoServiceOriginal = new PsInfoService(
            app(CustomFieldServiceInterface::class)
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->psInfoServiceOriginal);

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

        // Prepare Params For Success
        $loc00001 = 'loc00001';
        $customFieldValues = [
            $loc00001 => 'Testing1',
        ];
        $parentId = 1;

        // Prepare Data For Success
        CustomField::factory()->create([
            'core_keys_id' => $loc00001,
        ]);

        // Call the method
        $this->psInfoServiceOriginal->save(
            Constants::locationCity,
            $customFieldValues,
            $parentId,
            LocationCityInfo::class,
            'location_city_id'
        );

        $this->assertDatabaseHas(LocationCityInfo::tableName, [
            'core_keys_id' => $loc00001,
        ]);

        // Prepare Params For Fail
        $customFieldValues = [];
        $parentId = 1;

        // Call the method
        $failResult = $this->psInfoServiceOriginal->save(
            Constants::locationCity,
            $customFieldValues,
            $parentId,
            LocationCityInfo::class,
            'location_city_id'
        );

        $this->assertNull($failResult);

    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Params For Success
        $loc00001 = 'loc00001';
        $value = 'Testing Update';
        $customFieldValues = [
            $loc00001 => $value,
        ];
        $parentId = 1;

        // Prepare Data For Success
        LocationCityInfo::factory()->create([
            'core_keys_id' => $loc00001,
        ]);

        CustomField::factory()->create([
            'core_keys_id' => $loc00001,
        ]);

        // Call the method
        $this->psInfoServiceOriginal->update(
            Constants::locationCity,
            $customFieldValues,
            $parentId,
            LocationCityInfo::class,
            'location_city_id'
        );

        $this->assertDatabaseHas(LocationCityInfo::tableName, [
            'value' => $value,
        ]);

        // Prepare Params For Fail
        $customFieldValues = [];
        $parentId = 1;

        // Call the method
        $failResult = $this->psInfoServiceOriginal->update(
            Constants::locationCity,
            $customFieldValues,
            $parentId,
            LocationCityInfo::class,
            'location_city_id'
        );

        $this->assertNull($failResult);

    }

    public function test_deleteAll()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Params For Success
        $loc00001 = 'loc00001';
        $value = 'Testing Update';
        $customFieldValues = [
            $loc00001 => $value,
        ];
        $parentId = 1;

        // Prepare Data For Success
        LocationCityInfo::factory()->create([
            'core_keys_id' => $loc00001,
        ]);

        CustomField::factory()->create([
            'core_keys_id' => $loc00001,
        ]);

        // Call the method
        $LocationCityInfos = $this->psInfoServiceOriginal->getAll(
            LocationCityInfo::class,
            $parentId,
            'location_city_id',
            noPagination: Constants::yes
        );
        $this->psInfoServiceOriginal->deleteAll($LocationCityInfos);

        $result = $this->psInfoServiceOriginal->get(
            LocationCityInfo::class,
            $parentId,
            $loc00001,
            'location_city_id'
        );

        $this->assertNull($result);
    }
}
