<?php

namespace CommonUtils\Tests\DI\TestClass;

use DI\Annotation\Inject;

class TestClassMain
{
    private TestClassWithConstructorDependency $classWithConstructorDependency;
    private TestClassWithConstructorDependencyChain $classWithConstructorDependencyChain;
    private TestClassWithoutDependencies $classWithoutDependenciesInjectedOnConstructor;
    /** @Inject() */
    private TestClassWithoutDependencies $classWithoutDependenciesInjectedWithAnnotation;

    private string $stringDependency;

    private TestInterface $interfaceImplementationInjectedOnConstructor;
    /** @Inject() */
    private TestInterface $interfaceImplementationInjectedWithAnnotation;
    /** @Inject("customName") */
    private TestInterface $interfaceImplementationInjectedWithAnnotationAndCustomName;

    public function __construct(
        TestClassWithoutDependencies $classWithoutDependenciesInjectedOnConstructor,
        TestClassWithConstructorDependency $classWithConstructorDependency,
        TestClassWithConstructorDependencyChain $classWithConstructorDependencyChain,
        string $stringDependency,
        TestInterface $interfaceImplementationInjectedOnConstructor
    ) {
        $this->classWithoutDependenciesInjectedOnConstructor = $classWithoutDependenciesInjectedOnConstructor;
        $this->classWithConstructorDependency = $classWithConstructorDependency;
        $this->classWithConstructorDependencyChain = $classWithConstructorDependencyChain;
        $this->stringDependency = $stringDependency;
        $this->interfaceImplementationInjectedOnConstructor = $interfaceImplementationInjectedOnConstructor;
    }

    public function callDependenciesName(): array
    {
        return [
            'classWithoutDependenciesInjectedOnConstructor' => $this->classWithoutDependenciesInjectedOnConstructor->getName(),
            'classWithConstructorDependency' => $this->classWithConstructorDependency->getName(),
            'classWithConstructorDependencyChain' => $this->classWithConstructorDependencyChain->getName(),
            'stringDependency' => $this->stringDependency,
            'interfaceImplementationInjectedOnConstructor' => $this->interfaceImplementationInjectedOnConstructor->getName(),
            'classWithoutDependenciesInjectedWithAnnotation' => $this->classWithoutDependenciesInjectedWithAnnotation->getName(),
            'interfaceImplementationInjectedWithAnnotation' => $this->interfaceImplementationInjectedWithAnnotation->getName(),
            'interfaceImplementationInjectedWithAnnotationAndCustomName' => $this->interfaceImplementationInjectedWithAnnotationAndCustomName->getName(),
        ];
    }
}
