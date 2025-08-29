<?php

namespace Tests\Unit\Image;

use App\Helpers\PsTestHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Http\Services\Image\WaterMarkService;
use Tests\TestCase;

class WaterMarkServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WaterMarkService $waterMarkService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->waterMarkService = app(WaterMarkService::class);

    }

    // public function test_applyWatermark() {}

    /**
     * getWaterMarkImageSize
     *
     * @return void
     */
    public function test_getWaterMarkImageSize()
    {

        $reflection = new \ReflectionClass($this->waterMarkService);
        $method = $reflection->getMethod('getWaterMarkImageSize');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->waterMarkService, [1000, 250, 250]);
        $this->assertEquals(25, $result);

        $result = $method->invokeArgs($this->waterMarkService, [2000, 250, 250]);
        $this->assertEquals(50, $result);

        $result = $method->invokeArgs($this->waterMarkService, [5, 250, 250]);
        $this->assertEquals(125, $result);

        $result = $method->invokeArgs($this->waterMarkService, [5000, 500, 500]);
        $this->assertEquals(250, $result);

        $result = $method->invokeArgs($this->waterMarkService, [10, 250, 250]);
        $this->assertEquals(25, $result);

        $result = $method->invokeArgs($this->waterMarkService, [9, 250, 250]);
        $this->assertEquals(225, $result);

        $result = $method->invokeArgs($this->waterMarkService, [1, 500, 250]);
        $this->assertEquals(50, $result);

        $result = $method->invokeArgs($this->waterMarkService, [1, 250, 500]);
        $this->assertEquals(50, $result);

        $result = $method->invokeArgs($this->waterMarkService, [1, 0, 0]);
        $this->assertEquals(0, $result);

        $result = $method->invokeArgs($this->waterMarkService, [1, -10, -10]);
        $this->assertEquals(0, $result);
    }

    public function test_convertSize()
    {

        $psTestHelper = new PsTestHelper($this->waterMarkService);

        $result1 = $psTestHelper->invokePrivateMethod('convertSize', [1000]);
        $this->assertEquals(1, $result1);

        $result1 = $psTestHelper->invokePrivateMethod('convertSize', [10]);
        $this->assertEquals(1, $result1);

        $result1 = $psTestHelper->invokePrivateMethod('convertSize', [1]);
        $this->assertEquals(1, $result1);

        $result1 = $psTestHelper->invokePrivateMethod('convertSize', [0]);
        $this->assertEquals(0, $result1);

        $result1 = $psTestHelper->invokePrivateMethod('convertSize', [-1]);
        $this->assertEquals(1, $result1);

    }

    public function test_getPosition()
    {
        $psTestHelper = new PsTestHelper($this->waterMarkService);

        // bottom-right
        $beSetting = [
            'position' => 'bottom-right',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('bottom-right', $result[0]);
        $this->assertEquals(1, $result[1]);
        $this->assertEquals(1, $result[2]);

        // 'bottom'
        $beSetting = [
            'position' => 'bottom',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('bottom', $result[0]);
        $this->assertEquals(0, $result[1]);
        $this->assertEquals(1, $result[2]);

        // 'bottom-left'
        $beSetting = [
            'position' => 'bottom-left',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('bottom-left', $result[0]);
        $this->assertEquals(1, $result[1]);
        $this->assertEquals(1, $result[2]);

        // 'top-right
        $beSetting = [
            'position' => 'top-right',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('top-right', $result[0]);
        $this->assertEquals(1, $result[1]);
        $this->assertEquals(1, $result[2]);

        // 'top'
        $beSetting = [
            'position' => 'top',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('top', $result[0]);
        $this->assertEquals(0, $result[1]);
        $this->assertEquals(1, $result[2]);

        // 'top-left'
        $beSetting = [
            'position' => 'top-left',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('top-left', $result[0]);
        $this->assertEquals(1, $result[1]);
        $this->assertEquals(1, $result[2]);

        // 'left'
        $beSetting = [
            'position' => 'left',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('left', $result[0]);
        $this->assertEquals(1, $result[1]);
        $this->assertEquals(1, $result[2]);

        // 'center'
        $beSetting = [
            'position' => 'center',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('center', $result[0]);
        $this->assertEquals(0, $result[1]);
        $this->assertEquals(0, $result[2]);

        // 'right'
        $beSetting = [
            'position' => 'right',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertEquals('right', $result[0]);
        $this->assertEquals(1, $result[1]);
        $this->assertEquals(1, $result[2]);

        // no valid position
        $beSetting = [
            'position' => 'NA',
            'padding' => 1,
        ];
        $beSettingObject = json_decode(json_encode($beSetting));

        $result = $psTestHelper->invokePrivateMethod('getPosition', [$beSettingObject]);
        $this->assertNull($result);

    }

    public function test_isRequireWatermark()
    {
        $result = $this->waterMarkService->isRequireWatermark('item');
        $this->assertTrue($result);

        $result = $this->waterMarkService->isRequireWatermark('preview');
        $this->assertTrue($result);

        $result = $this->waterMarkService->isRequireWatermark('background');
        $this->assertTrue($result);

        $result = $this->waterMarkService->isRequireWatermark('chatApi');
        $this->assertTrue($result);

        $result = $this->waterMarkService->isRequireWatermark('itemMulti');
        $this->assertTrue($result);

        $result = $this->waterMarkService->isRequireWatermark('itemmm');
        $this->assertFalse($result);

    }
}
