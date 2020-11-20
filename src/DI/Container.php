<?php

namespace CommonUtils\DI;

use DI\Container as DIContainer;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use InvalidArgumentException;

class Container
{
    private static ?DIContainer $container = null;
    private static string $definitionsFilePath;

    private function __construct()
    {
    }

    public static function setDefinitionsFilePath(string $definitionsFilePath): void
    {
        if (!is_file($definitionsFilePath)) {
            throw new InvalidArgumentException("The DI Container definitions config file could not be found at: $definitionsFilePath");
        }
        self::$definitionsFilePath = $definitionsFilePath;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function get(string $name)
    {
        return self::getContainer()->get($name);
    }

    /**
     * @param string $name
     * @param array $parameters Map<string, string> = <parameterName => className>
     *
     * @return object
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function make(string $name, array $parameters = []): object
    {
        return self::getContainer()->make($name, $parameters);
    }

    private static function getContainer(): DIContainer
    {
        if (self::$container === null) {
            self::$container = self::buildContainer();
        }

        return self::$container;
    }

    private static function buildContainer(): DIContainer
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAnnotations(true);
        $containerBuilder->addDefinitions(self::$definitionsFilePath);

        return $containerBuilder->build();
    }
}
