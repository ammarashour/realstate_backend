<?php

namespace App\Helpers;

class PsTestHelper
{
    private $reflection;

    private $object;

    public function __construct($object)
    {
        $this->object = $object;
        $this->reflection = new \ReflectionClass(get_class($object));
    }

    public function invokePrivateMethod($methodName, array $parameters = [])
    {
        $method = $this->reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->object, $parameters);
    }

    public static function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
