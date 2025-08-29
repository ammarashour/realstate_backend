<?php

namespace Tests\Unit\AvailableCurrency;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

use Modules\Core\Http\Controllers\Backend\Controllers\AvailableCurrency\AvailableCurrencyController;
use Modules\Core\Http\Requests\StoreAvailableCurrencyRequest;
use Modules\Core\Http\Requests\UpdateAvailableCurrencyRequest;
use Modules\Core\http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Tests\TestCase;

class AvailableCurrencyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $availableCurrencyController;

    protected $availableCurrencyControllerOriginal;

    protected $availableCurrencyService;

    protected $coreFieldFilterSettingService;

    protected $storeAvailableCurrencyRequest;

    protected $updateAvailableCurrencyRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->availableCurrencyService = Mockery::mock(AvailableCurrencyService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);

        // Mock StoreAvailableCurrencyRequest
        $this->storeAvailableCurrencyRequest = Mockery::mock(StoreAvailableCurrencyRequest::class);
        $this->updateAvailableCurrencyRequest = Mockery::mock(UpdateAvailableCurrencyRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the AvailableCurrencyController to mock the handlePermission method
        $this->availableCurrencyController = Mockery::mock(AvailableCurrencyController::class, [
            $this->availableCurrencyService,
            $this->coreFieldFilterSettingService,
        ])->makePartial();

        $this->availableCurrencyControllerOriginal = new AvailableCurrencyController(
            $this->availableCurrencyService,
            $this->coreFieldFilterSettingService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->availableCurrencyControllerOriginal);
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

    public function test_store()
    {
        $this->storeAvailableCurrencyRequest->shouldReceive('validated')->twice()->andReturn([
            'currency_short_form' => 'PSC',
            'currency_symbol' => 'PS',
            'name' => 'PS Coin',
        ]);

        // Mock availableCurrencyService
        $this->availableCurrencyService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Available Currency Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->availableCurrencyController->store($this->storeAvailableCurrencyRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->availableCurrencyService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->availableCurrencyController->store($this->storeAvailableCurrencyRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        $this->updateAvailableCurrencyRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'currency_short_form' => 'PSC',
            'currency_symbol' => 'PS',
            'name' => 'PS Coin',
        ]);

        // Mock availableCurrencyService
        $this->availableCurrencyService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Available Currency Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->availableCurrencyController->update($this->updateAvailableCurrencyRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->availableCurrencyService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->availableCurrencyController->update($this->updateAvailableCurrencyRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a currency for testing
        $available_currency = AvailableCurrency::factory()->create();

        // Mock availableCurrencyService
        $this->availableCurrencyService->shouldReceive('get')->once()->with($available_currency->id)->andReturn($available_currency);

        // Ensure handlePermission does nothing
        $this->availableCurrencyController->shouldReceive('handlePermissionWithModel')
            ->with($available_currency, Constants::deleteAbility);

        $this->availableCurrencyService->shouldReceive('delete')->once()->with($available_currency->id)->andReturn([
            'msg' => 'Available currency deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->availableCurrencyController->destroy($available_currency->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Available currency deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareCreateData()
    {

        $this->coreFieldFilterSettingService->shouldReceive('getCoreFields')
            ->once()->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareIndexData()
    {
        // Create a fake request with parameters
        $inputs = [
            'search' => 'keyword',
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'row' => 10,
        ];

        foreach ($inputs as $key => $value) {
            $this->request->shouldReceive('input')
                ->with($key)
                ->andReturn($value);
        }

        $conds = [
            'searchterm' => $inputs['search'],
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
        ];

        $this->availableCurrencyService->shouldReceive('getAll')
            ->once()
            ->with(
                null,
                null,
                null,
                null,
                false,
                $inputs['row'],
                $conds
            )
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);
        $this->assertArrayHasKey('currencies', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareEditData()
    {
        $id = 1;

        $this->coreFieldFilterSettingService->shouldReceive('getCoreFields')
            ->once()
            ->with(
                null,
                null,
                null,
                1,
                null,
                null,
                null,
                null,
                null,
                Constants::availableCurrency
            )->andReturn([]);

        $this->availableCurrencyService->shouldReceive('get')
            ->once()
            ->with($id)
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_controlFieldArr()
    {

        $result = $this->psTestHelper->invokePrivateMethod('controlFieldArr', []);

        $this->assertNotNull($result);
        $this->assertEquals('core__be_action', $result[0]->label);
        $this->assertEquals('action', $result[0]->field);
        $this->assertEquals('Action', $result[0]->type);
        $this->assertEquals(false, $result[0]->sort);
        $this->assertEquals(0, $result[0]->ordering);
    }
}
