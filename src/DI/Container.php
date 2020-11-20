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
    private static ?string $definitionsFilePath = null;

    private function __construct()
    {
    }

    public static function setDefinitionsFilePath(string $definitionsFilePath): void
    {
        if (!is_file($definitionsFilePath)) {
            throw new InvalidArgumentException(
                "The DI Container definitions config file could not be found at path: $definitionsFilePath"
            );
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
     * @return mixed
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function make(string $name, array $parameters = [])
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

        if (is_file(self::$definitionsFilePath)) {
            $containerBuilder->addDefinitions(self::$definitionsFilePath);
        }

        return $containerBuilder->build();
    }
}
