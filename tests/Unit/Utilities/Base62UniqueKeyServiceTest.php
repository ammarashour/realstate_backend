<?php

namespace Tests\Unit\Utilities;

use Modules\Core\Http\Services\Configuration\SettingService;
use Modules\Core\Http\Services\Utilities\ChunkUpdateService;
use Modules\Core\Http\Services\Utilities\DynamicLinkService;
use Tests\TestCase;

class Base62UniqueKeyServiceTest extends TestCase
{
    protected $dynamicLinkService;

    protected function setUp(): void
    {
        $this->dynamicLinkService = new DynamicLinkService(
            new SettingService(),
            new ChunkUpdateService()
        );
    }

    public function test_it_outputs_unique_keys()
    {
        // Access the method dynamically using Reflection
        $reflection = new \ReflectionClass($this->dynamicLinkService);
        $method = $reflection->getMethod('encodeBase62');
        $method->setAccessible(true);

        // Store the unique keys
        $uniqueNumbers = [];

        // Generate the keys
        for ($i = 0; $i < 100001; $i++) {
            $uniqueNumbers[] = "IT-" .$method->invokeArgs($this->dynamicLinkService, [$i]);
        }

        // Check if all generated keys are unique
        $uniqueKeys = array_unique($uniqueNumbers);

        // Assert that the original array length is equal to the unique array length
        $this->assertCount(count($uniqueNumbers), $uniqueKeys, 'The generated keys are not unique.');
    }
}
