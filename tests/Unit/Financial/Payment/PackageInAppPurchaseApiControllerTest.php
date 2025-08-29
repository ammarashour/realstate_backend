<?php

namespace tests\Unit\Financial\Payment;

use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Config\ps_constant;
use App\Helpers\PsTestHelper;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Financial\PackageInAppPurchaseSettingApiController;
use Modules\Core\Http\Services\Financial\PackageInAppPurchaseSettingService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Transformers\Api\App\V1_0\Financial\PackageInAppPurchaseSettingApiResource;
use Modules\Payment\Transformers\Api\App\V1_0\Payment\PackageWithPurchasedCountApiResource;

class PackageInAppPurchaseApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $packageInAppPurhcaseApiController;

    protected $packageInAppPurhcaseApiControllerOriginal;

    protected $packageInAppPurchaseSettingService;

    protected $userAccessApiTokenService;

    protected $paymentSettingService;

    protected $psTestHelper;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->packageInAppPurchaseSettingService = Mockery::mock(PackageInAppPurchaseSettingService::class);
        $this->userAccessApiTokenService = Mockery::mock(UserAccessApiTokenService::class);
        $this->paymentSettingService = Mockery::mock(PaymentSettingService::class);
       
        $this->packageInAppPurhcaseApiController = Mockery::mock(PackageInAppPurchaseSettingApiController::class, [
            $this->packageInAppPurchaseSettingService,
            $this->userAccessApiTokenService,
            $this->paymentSettingService
        ])->makePartial();

        $this->packageInAppPurhcaseApiControllerOriginal = new PackageInAppPurchaseSettingApiController(
            $this->packageInAppPurchaseSettingService,
            $this->userAccessApiTokenService,
            $this->paymentSettingService
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->packageInAppPurhcaseApiControllerOriginal);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testIndex()
    {

        $device_token = ps_constant::deviceTokenKeyFromApi;

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with(ps_constant::deviceTokenKeyFromApi)->andReturn($device_token);
        $request->shouldReceive('login_user_id')->andReturn(1);
        $request->shouldReceive('all')->andReturn([]); 
        $request->shouldReceive('route')->andReturn([]); 

        $this->userAccessApiTokenService->shouldReceive('getUserAccessApiToken')->with(1, $device_token, null);

        $request->shouldReceive('offset')->andReturn(0);
        $request->shouldReceive('limit')->andReturn(10);

        $this->paymentSettingService->shouldReceive('getPaymentInfos')
            ->andReturn(collect([]));

        $result = $this->packageInAppPurhcaseApiController->index($request);

        $this->assertInstanceOf(PackageInAppPurchaseSettingApiResource::class, $result->first());
    }

    public function testPackagePurchasedCount()
    {

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('header')->with('deviceTokenKeyFromApi')->andReturn('device_token');
        $request->shouldReceive('login_user_id')->andReturn(1);

        $this->userAccessApiTokenService->shouldReceive('getUserAccessApiToken')->with(1, 'device_token');

        $request->shouldReceive('offset')->andReturn(0);
        $request->shouldReceive('limit')->andReturn(10);

        $this->paymentSettingService->shouldReceive('getPaymentInfos')
            ->andReturn(collect([]));

        $result = $this->packageInAppPurhcaseApiController->packagePurchasedCount($request);

        $this->assertInstanceOf(PackageWithPurchasedCountApiResource::class, $result->first());
    }
}
