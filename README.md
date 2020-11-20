# Common-Utils project
Collection of common utility classes 
* [DI Container](#di-container)
* [Config Parser](#config-parser)
* [Command Listener and Execution Stats](#command-utils)

## DI Container
Helper class over the [php-di](https://php-di.org/doc/) libary.

Get class instance with all dependencies injected (autowired).

#### Usage:
```
$myClass = Container::get(MyClass::class);
$myClass->myMethod();
```

If you need to inject some of the arguments manually, use `Container::make()`:
```
$myClass = Container::make(MyClass::class, [
    'user' => 'mihaitmf',
]);
```

#### Configuration
In certain situations it is helpful to use a Definitions configuration file:
* to map interfaces to implementations
* for classes that require primitive data types or arguments without type hint on constructor
* to use static factory methods

Example `di-config.php` file:
```
return [
    ClientInterface::class => autowire(Client::class),
    ConfigParser::class => factory(
        function () {
            return ConfigParser::fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini');
        }
    ),
];
```
Set DI configuration file in the bootstrap file, right after requiring the class loader:
```
require_once __DIR__ . '/vendor/autoload.php';

Container::setDefinitionsFilePath(__DIR__ . DIRECTORY_SEPARATOR . 'di-config.php');
```

## Config Parser

## Command Utils
