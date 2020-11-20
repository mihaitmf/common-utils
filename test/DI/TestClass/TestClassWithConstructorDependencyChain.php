<?php

namespace CommonUtils\Tests\DI\TestClass;

class TestClassWithConstructorDependencyChain implements TestInterface
{
    private TestClassWithStringDependency $classWithStringDependency;

    public function __construct(TestClassWithStringDependency $classWithStringDependency)
    {
        $this->classWithStringDependency = $classWithStringDependency;
    }

    public function getName(): string
    {
        return self::class . ' with ' . $this->classWithStringDependency->getName();
    }
}
