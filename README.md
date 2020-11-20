# Common-Utils project
Collection of common utility classes 
* [DI Container](#di-container)
* [Config Parser](#config-parser)
* [Command Listener and Execution Stats](#command-utils)

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
    ConfigIniParser::class => factory(
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
Parser for config ini files.

Example of `config.ini` file:
```
[database]
; this is a comment
host = "localhost"
name = "my_db"

[owner]

name=John Doe
organization=Acme Inc.

[settings]
data[] = "1"
data[] = "2"
```

#### Usage
It requires the path to the config ini file.
```
$config = ConfigIniParser::fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini');
$databaseName = $config->database->name;
$ownerOrganization = $config->owner->organization;
```

## Command Utils
