<?php

namespace CommonUtils\Tests\DI;

use CommonUtils\DI\Container;
use CommonUtils\Tests\DI\TestClass\TestClassMain;
use CommonUtils\Tests\DI\TestClass\TestClassWithConstructorDependency;
use CommonUtils\Tests\DI\TestClass\TestClassWithConstructorDependencyChain;
use CommonUtils\Tests\DI\TestClass\TestClassWithoutDependencies;
use CommonUtils\Tests\DI\TestClass\TestClassWithStringDependency;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainerGet(): void
    {
        Container::setDefinitionsFilePath(
            __DIR__ . DIRECTORY_SEPARATOR . 'TestClass' . DIRECTORY_SEPARATOR . 'di-config.php'
        );

        $object = Container::get(TestClassMain::class);
        $actualResult = $object->callDependenciesName();

        $expectedResult = [
            'classWithoutDependenciesInjectedOnConstructor' => TestClassWithoutDependencies::class,
            'classWithConstructorDependency' => TestClassWithConstructorDependency::class . ' with ' . TestClassWithoutDependencies::class,
            'classWithConstructorDependencyChain' => TestClassWithConstructorDependencyChain::class . ' with ' . TestClassWithStringDependency::class . ' with My string argument in TestClassWithStringDependency',
            'stringDependency' => 'My string argument in TestClassMain',
            'interfaceImplementationInjectedOnConstructor' => TestClassWithoutDependencies::class,
            'classWithoutDependenciesInjectedWithAnnotation' => TestClassWithoutDependencies::class,
            'interfaceImplementationInjectedWithAnnotation' => TestClassWithoutDependencies::class,
            'interfaceImplementationInjectedWithAnnotationAndCustomName' => TestClassWithConstructorDependency::class . ' with ' . TestClassWithoutDependencies::class,
        ];

        self::assertSame($expectedResult, $actualResult, 'Container::get did not return the correct instance');
    }

    public function testSimpleContainerGetWithoutDefinitionsFile(): void
    {
        $object = Container::get(TestClassWithoutDependencies::class);
        self::assertSame(
            TestClassWithoutDependencies::class,
            $object->getName(),
            'Container::get did not return the correct instance'
        );
    }
}
