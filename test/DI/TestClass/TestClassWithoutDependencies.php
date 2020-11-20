<?php

namespace CommonUtils\Tests\DI\TestClass;

class TestClassWithoutDependencies implements TestInterface
{
    public function getName(): string
    {
        return self::class;
    }
}
