<?php

namespace tests\Unit\Financial\Payment;

use App\Helpers\PsTestHelper;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Constants\Constants;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Entities\PaymentAttribute;
use App\Models\User;
use Modules\Payment\Http\Services\PaymentService;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Payment\Entities\CoreKeyPaymentRelation;
use Modules\Core\Http\Services\AvailableCurrency\AvailableCurrencyService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Http\Contracts\Financial\Payment\PackageInAppPurchase\PackageInAppPurchaseServiceInterface;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Http\Services\Configuration\CoreKeyService;
use Modules\Core\Http\Services\Financial\CoreKeyPaymentRelationService;
use Modules\Core\Http\Services\Financial\PackageInAppPurchaseSettingService;
use Modules\Core\Http\Services\Financial\PaymentAttributeService;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class PackageInAppPurchaseSettingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $PackageInAppPurchaseSettingService;

    protected $PackageInAppPurchaseSettingServiceOriginal;

    protected $userAccessApiTokenService;

    protected $coreKeyPaymentRelationService;

    protected $paymentService;

    protected $coreKeyService;

    protected $paymentSettingService;

    protected $paymentAttributeService;

    protected $availableCurrencyService;

    protected $psTestHelper;

    protected $currency;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userAccessApiTokenService = Mockery::mock(UserAccessApiTokenService::class);
        $this->coreKeyPaymentRelationService = Mockery::mock(CoreKeyPaymentRelationService::class);
        $this->paymentService = Mockery::mock(PaymentService::class);
        $this->coreKeyService = Mockery::mock(CoreKeyService::class);
        $this->paymentSettingService = Mockery::mock(PaymentSettingService::class);
        $this->paymentAttributeService = Mockery::mock(PaymentAttributeService::class);
        $this->availableCurrencyService = Mockery::mock(AvailableCurrencyService::class);


        $this->PackageInAppPurchaseSettingService = Mockery::mock(PackageInAppPurchaseSettingService::class, [
            $this->userAccessApiTokenService,
            $this->coreKeyPaymentRelationService,
            $this->paymentService,
            $this->coreKeyService,
            $this->paymentSettingService,
            $this->paymentAttributeService,
            $this->availableCurrencyService,
        ])->makePartial();

        $this->PackageInAppPurchaseSettingServiceOriginal = new PackageInAppPurchaseSettingService(
            $this->userAccessApiTokenService,
            $this->coreKeyPaymentRelationService,
            $this->paymentService,
            $this->coreKeyService,
            $this->paymentSettingService,
            $this->paymentAttributeService,
            $this->availableCurrencyService,
        );

        $this->currency = ItemCurrency::factory()->create();
        $this->user = User::factory()->create();
        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->PackageInAppPurchaseSettingServiceOriginal);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up Mockery
        Mockery::close();
    }

    public function test_save_success()
    {

        $packageIAPData = [
            'in_app_purchase_prd_id' => 'testing12',
            'description' => 'asdfasdfasdf',
            'type' => 'Android',
            'count' => 4,
            'price' => 3000,
            'status' => true,
            'currency_id' => $this->currency->id,
            'added_user_id' => $this->user->id,
            'core_keys_id' => 'ps-pmt00044'
        ];

        $this->PackageInAppPurchaseSettingService
            ->shouldReceive('save')
            ->with($packageIAPData);
        $this->PackageInAppPurchaseSettingService->save($packageIAPData);
        $paymentInfo = $this->PackageInAppPurchaseSettingService->get(1);

        $this->assertEquals($packageIAPData['core_keys_id'], $paymentInfo->core_keys_id);
    }

    public function test_save_exception()
    {
        $packageIAPData = [
            'in_app_purchase_prd_id' => 'testing12',
            'description' => 'asdfasdfasdf',
            'type' => 'Android',
            'count' => 4,
            'price' => 3000,
            'status' => true,
            'currency_id' => $this->currency->id,
            'added_user_id' => $this->user->id,
            'core_keys_id' => 'ps-pmt00044',
        ];

        $errorMessage = 'Error Message!';
        $this->PackageInAppPurchaseSettingService->shouldReceive('save')
            ->once()
            ->with($packageIAPData)
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($errorMessage);

        $result = $this->PackageInAppPurchaseSettingService->save($packageIAPData);

        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update_success()
    {
        $packageIAPData = [
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
        ];

        $this->PackageInAppPurchaseSettingService
            ->shouldReceive('update')
            ->with($packageIAPData)
            ->andReturn([]);
        $this->PackageInAppPurchaseSettingService->update($packageIAPData['id'], $packageIAPData);

        $paymentInfo = $this->PackageInAppPurchaseSettingService->get(1);

        $this->assertEquals($packageIAPData['core_keys_id'], $paymentInfo->core_keys_id);
    }

    public function test_update_exception()
    {
        $packageIAPData = [
            'in_app_purchase_prd_id' => 'testing12',
            'description' => 'asdfasdfasdf',
            'type' => 'Android',
            'count' => 4,
            'price' => 3000,
            'status' => true,
            'currency_id' => $this->currency->id,
            'added_user_id' => $this->user->id,
            'core_keys_id' => 'ps-pmt00044',
        ];

        $errorMessage = 'Error Message!';
        $this->PackageInAppPurchaseSettingService->shouldReceive('save')
            ->once()
            ->with($packageIAPData)
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage($errorMessage);

        $result = $this->PackageInAppPurchaseSettingService->save($packageIAPData);

        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a city for testing
        $paymentAttr = PaymentAttribute::factory()->create();
        $conds = [
            'attribute_key' => Constants::pmtAttrPackageIapStatusCol,
            'core_keys_id' => $paymentAttr->core_keys_id
        ];
        // Call the setStatus method
        $result = $this->PackageInAppPurchaseSettingService->setStatus($paymentAttr->id, Constants::unPublish);

        $this->paymentAttributeService->shouldReceive('getPaymentAttribute')->once()
            ->with(null, $conds)
            ->andReturn([]);

        // Assertions
        $this->assertInstanceOf(PaymentAttribute::class, $result);
        $this->assertEquals(Constants::unPublish, $result->status);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }
}
