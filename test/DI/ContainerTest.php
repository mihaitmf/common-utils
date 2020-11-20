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
    private const DEFINITIONS_FILE_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'TestClass' . DIRECTORY_SEPARATOR . 'di-config.php';

    public function testContainerGetSuccess(): void
    {
        Container::setDefinitionsFilePath(self::DEFINITIONS_FILE_PATH);

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

    public function testContainerGetWithoutDefinitionsFile(): void
    {
        $object = Container::get(TestClassWithoutDependencies::class);

        self::assertSame(
            TestClassWithoutDependencies::class,
            $object->getName(),
            'Container::get did not return the correct instance'
        );
    }

    public function testContainerMakeSuccess(): void
    {
        Container::setDefinitionsFilePath(self::DEFINITIONS_FILE_PATH);

        $stringDependency = 'My string dependency on constructor, from container-make';

        $object = Container::make(TestClassMain::class, [
            'stringDependency' => $stringDependency,
            'interfaceImplementationInjectedOnConstructor' => Container::get(TestClassWithStringDependency::class),
        ]);

        $actualResult = $object->callDependenciesName();

        $expectedResult = [
            'classWithoutDependenciesInjectedOnConstructor' => TestClassWithoutDependencies::class,
            'classWithConstructorDependency' => TestClassWithConstructorDependency::class . ' with ' . TestClassWithoutDependencies::class,
            'classWithConstructorDependencyChain' => TestClassWithConstructorDependencyChain::class . ' with ' . TestClassWithStringDependency::class . ' with My string argument in TestClassWithStringDependency',
            'stringDependency' => $stringDependency,
            'interfaceImplementationInjectedOnConstructor' => TestClassWithStringDependency::class . ' with My string argument in TestClassWithStringDependency',
            'classWithoutDependenciesInjectedWithAnnotation' => TestClassWithoutDependencies::class,
            'interfaceImplementationInjectedWithAnnotation' => TestClassWithoutDependencies::class,
            'interfaceImplementationInjectedWithAnnotationAndCustomName' => TestClassWithConstructorDependency::class . ' with ' . TestClassWithoutDependencies::class,
        ];

        self::assertSame($expectedResult, $actualResult, 'Container::make did not return the correct instance');
    }

    public function testContainerMakeWithoutDefinitionsFile(): void
    {
        $stringDependency = 'My string from container-make';

        $object = Container::make(TestClassWithStringDependency::class, [
            'stringDependency' => $stringDependency,
        ]);

        self::assertSame(
            TestClassWithStringDependency::class . " with $stringDependency",
            $object->getName(),
            'Container::get did not return the correct instance'
        );
    }
}
