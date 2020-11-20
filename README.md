# Common-Utils project
[![Github Workflow Status](https://github.com/mihaitmf/common-utils/workflows/PHP%20Composer%20Tests/badge.svg)](https://github.com/mihaitmf/common-utils/actions?query=workflow%3A%22PHP+Composer+Tests%22)
[![Travis Build Status](https://travis-ci.com/mihaitmf/common-utils.svg?branch=main)](https://travis-ci.com/mihaitmf/common-utils)

Collection of common utility classes 
* [DI Container](#di-container)
* [Config Parser](#config-parser)
* [Command Listener and Execution Stats](#command-utils)

## Requirements
- [![Minimum PHP Version](https://img.shields.io/badge/php-%3E=7.4-8892BF.svg)](https://php.net/)
- `composer`

## DI Container
Helper class over the [php-di](https://php-di.org/doc/) libary.

#### Usage
##### Container::get()
Get class instance with all dependencies injected (autowired).
```
$myClass = Container::get(MyClass::class);
$myClass->myMethod();
```

##### Container::make()
Useful for creating objects that should not be stored inside the container
(i.e. that are not services, or that are not `stateless`), but that have dependencies.

It is also useful if you want to override some parameters of an object's constructor.
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
    Config::class => factory(
        function () {
            return ConfigIniParser::fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini');
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
Parser for ini files.

Example of `config.ini` file:
```
[database]
; this is a comment
name = "my_db"
host = "localhost"

[settings]
data[] = "1"
data[] = "2"
```

#### Usage
The Parser requires the path to the config ini file and returns a `Config` object.

To access the config values, one can use either the object or the array access operators.
```
$config = ConfigIniParser::fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini');

$databaseName = $config->database->name;
$databaseHost = $config['database']['host'];

$settingsData1 = $config->settings->data->{0};
$settingsData2 = $config->settings->data[1];
```

## Command Utils
