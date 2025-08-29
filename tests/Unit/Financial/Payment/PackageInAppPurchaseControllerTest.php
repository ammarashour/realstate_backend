<?php

namespace tests\Unit\Financial\Payment;

use App\Helpers\PsTestHelper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Mockery;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Entities\PaymentAttribute;
use App\Models\User;
use App\Http\Controllers\PsController;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Http\Controllers\Backend\Controllers\Financial\PackageInAppPurchaseSettingController;
use Modules\Core\Http\Requests\Financial\StorePackageInAppPurchaseRequest;
use Modules\Core\Http\Requests\Financial\UpdatePackageInAppPurchaseRequest;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Core\Http\Services\Financial\PackageInAppPurchaseSettingService;
use Modules\Core\Http\Services\Financial\PaymentAttributeService;

class PackageInAppPurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $packageInAppPurchaseController;

    protected $packageInAppPurchaseControllerOriginal;

    protected $packageInAppPurchaseSettingService;

    protected $paymentSettingService;

    protected $availableCurrencyService;

    protected $paymentAttributeService;

    protected $storePackageIAPRequest;

    protected $updatePackageIAPRequest;

    protected $psTestHelper;

    protected $currency;

    protected $user;

    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->packageInAppPurchaseSettingService = Mockery::mock(PackageInAppPurchaseSettingService::class);
        $this->paymentSettingService = Mockery::mock(PaymentSettingService::class);
        $this->paymentAttributeService = Mockery::mock(PaymentAttributeService::class);
        $this->availableCurrencyService = Mockery::mock(AvailableCurrencyService::class);

        // Mock storeLocationCityRequest
        $this->storePackageIAPRequest = Mockery::mock(StorePackageInAppPurchaseRequest::class);
        $this->updatePackageIAPRequest = Mockery::mock(UpdatePackageInAppPurchaseRequest::class);
        $this->request = Mockery::mock(Request::class);


        $this->packageInAppPurchaseController = Mockery::mock(PackageInAppPurchaseSettingController::class, [
            $this->packageInAppPurchaseSettingService,
            $this->paymentSettingService,
            $this->paymentAttributeService,
            $this->availableCurrencyService,
        ])->makePartial();

        $this->packageInAppPurchaseControllerOriginal = new PackageInAppPurchaseSettingController(
            $$this->packageInAppPurchaseSettingService,
            $this->paymentSettingService,
            $this->availableCurrencyService,
            $this->paymentAttributeService,
        );

        $this->currency = ItemCurrency::factory()->create();
        $this->user = User::factory()->create();
        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->packageInAppPurchaseControllerOriginal);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up Mockery
        Mockery::close();
    }

    public function test_save()
    {
        $this->storePackageIAPRequest->shouldReceive('validated')->twice()->andReturn([
            'in_app_purchase_prd_id' => 'testing12',
            'description' => 'asdfasdfasdf',
            'type' => 'Android',
            'count' => 4,
            'price' => 3000,
            'status' => true,
            'currency_id' => $this->currency->id,
            'added_user_id' => $this->user->id,
            'core_keys_id' => 'ps-pmt00044'
        ]);

        // Mock locationCityService
        $this->packageInAppPurchaseSettingService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->packageInAppPurchaseController->store($this->storePackageIAPRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->packageInAppPurchaseSettingService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->packageInAppPurchaseController->store($this->storePackageIAPRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {

        // Mock StoreBlogRequest
        $this->updatePackageIAPRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'in_app_purchase_prd_id' => 'testing12',
            'description' => 'asdfasdfasdf',
            'type' => 'Android',
            'count' => 4,
            'price' => 3000,
            'status' => true,
            'currency_id' => $this->currency->id,
            'added_user_id' => $this->user->id,
            'core_keys_id' => 'ps-pmt00044'
        ]);

        // Mock locationCityService
        $this->packageInAppPurchaseSettingService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->packageInAppPurchaseController->update($this->updatePackageIAPRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->packageInAppPurchaseSettingService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->packageInAppPurchaseController->update($this->updatePackageIAPRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a blog for testing
        $paymentInfo = PaymentInfo::factory()->create();

        // Mock BlogService
        $this->packageInAppPurchaseSettingService->shouldReceive('get')->once()->with($paymentInfo->id)->andReturn($paymentInfo);

        // Ensure handlePermission does nothing
        $this->packageInAppPurchaseController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::packageInAppPurchaseModule, ps_constant::deletePermission, $this->user->id);

        $this->packageInAppPurchaseSettingService->shouldReceive('delete')->once()->with($paymentInfo->id)->andReturn([
            'msg' => 'City deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->packageInAppPurchaseController->destroy($paymentInfo->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('City deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Blog Data
        $blog = PaymentAttribute::factory()->create(['status' => Constants::publish]);

        $conds = [
            'attribute_key' => Constants::pmtAttrPackageIapStatusCol,
            'core_keys_id' => 'psx00012'
        ];

        // Mock the get method to return the blog instance
        $this->paymentAttributeService->shouldReceive('getPaymentAttribute')->once()
            ->with(null, $conds)
            ->andReturn($blog);

        // Ensure handlePermission does nothing
        $this->packageInAppPurchaseController->shouldReceive('handlePermissionWithModel')
            ->with($blog, Constants::editAbility);

        // Mock the setStatus method
        $this->packageInAppPurchaseSettingService->shouldReceive('setStatus')->once()
            ->with($blog->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->packageInAppPurchaseController->statusChange($blog->id);

        // Assert the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareCreateData()
    {

        $result = $this->availableCurrencyService->shouldReceive('getAll')
            ->once()->andReturn([]);

        $this->assertNotNull($result);

        $this->assertArrayHasKey('availableCurrencies', $result);
    }

    public function test_prepareIndexData()
    {
        $currency = ItemCurrency::factory()->create();
        // Create a fake request with parameters
        $inputs = [
            'search' => 'keyword',
            'type' => 'Android',
            'currency_id' => $currency->id,
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'row' => 10,
            'payment_id' => Constants::packageInAppPurchasePaymentId,
        ];

        foreach ($inputs as $key => $value) {
            $this->request->shouldReceive('input')
                ->with($key)
                ->andReturn($value);
        }

        $conds = [
            'searchterm' => $inputs['search'],
            'type' => $inputs['type'],
            'currency_id' => $inputs['currency_id'],
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
            'payment_id' => $inputs['payment_id']
        ];

        $row = $inputs['row'];

        $relations = ['core_key'];
        $attributes = [
            Constants::pmtAttrPackageIapTypeCol,
            Constants::pmtAttrPackageIapCountCol,
            Constants::pmtAttrPackageIapPriceCol,
            Constants::pmtAttrPackageIapStatusCol,
            Constants::pmtAttrPackageIapCurrencyCol
        ];

        $this->paymentSettingService->shouldReceive('getPaymentInfos')
            ->once()
            ->with($relations, null, null, $conds, false, $row, $attributes)
            ->andReturn([]);

        $this->availableCurrencyService->shouldReceive('getAll')
            ->once()
            ->with(null, Constants::publish)
            ->andReturn([]);


        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('currencies', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
        $this->assertArrayHasKey('types', $result);
        $this->assertArrayHasKey('selected_type', $result);
        $this->assertArrayHasKey('selected_currency', $result);
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
