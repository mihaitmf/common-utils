<?php

namespace CommonUtils\Tests\DI\TestClass;

class TestClassWithConstructorDependency implements TestInterface
{
    private TestClassWithoutDependencies $classWithoutDependencies;

    public function __construct(TestClassWithoutDependencies $classWithoutDependencies)
    {
        $this->classWithoutDependencies = $classWithoutDependencies;
    }

    public function getName(): string
    {
        return self::class . ' with ' . $this->classWithoutDependencies->getName();
    }
}
