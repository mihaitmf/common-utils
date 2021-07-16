<?php
// see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
// and https://php-di.org/doc/ide-integration.html#phpstorm-integration
namespace PHPSTORM_META {

    override(
        \Psr\Container\ContainerInterface::get(0),
        map(
            [
                '' => '@',
            ]
        )
    );
    override(
        \DI\Container::get(0),
        map(
            [
                '' => '@',
            ]
        )
    );
    override(
        \DI\FactoryInterface::make(0),
        map(
            [
                '' => '@',
            ]
        )
    );
}
