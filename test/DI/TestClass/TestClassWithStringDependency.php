<?php

namespace CommonUtils\Tests\DI\TestClass;

class TestClassWithStringDependency implements TestInterface
{
    private string $stringDependency;

    public function __construct(string $stringDependency)
    {
        $this->stringDependency = $stringDependency;
    }

    public function getName(): string
    {
        return self::class . ' with ' . $this->stringDependency;
    }
}
