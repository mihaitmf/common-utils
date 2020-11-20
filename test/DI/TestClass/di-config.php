<?php

use CommonUtils\Tests\DI\TestClass\TestClassMain;
use CommonUtils\Tests\DI\TestClass\TestClassWithConstructorDependency;
use CommonUtils\Tests\DI\TestClass\TestClassWithoutDependencies;
use CommonUtils\Tests\DI\TestClass\TestClassWithStringDependency;
use CommonUtils\Tests\DI\TestClass\TestInterface;
use function DI\autowire;

return [
    TestClassMain::class => autowire()->constructorParameter(
        'stringDependency',
        'My string argument in TestClassMain'
    ),
    TestClassWithStringDependency::class => autowire()->constructor(
        'My string argument in TestClassWithStringDependency'
    ),
    TestInterface::class => autowire(TestClassWithoutDependencies::class),
    'customName' => autowire(TestClassWithConstructorDependency::class),
];
