# Common-Utils project
[![Github Workflow Status](https://github.com/mihaitmf/common-utils/workflows/PHP%20Composer%20Tests/badge.svg)](https://github.com/mihaitmf/common-utils/actions?query=workflow%3A%22PHP+Composer+Tests%22)
[![Travis Build Status](https://travis-ci.com/mihaitmf/common-utils.svg?branch=main)](https://travis-ci.com/mihaitmf/common-utils)

Collection of common utility classes 
* [DI Container](#di-container)
* [Config Parser](#config-parser)
* [Command Listener and Execution Stats](#command-utils)

## Requirements
- [![Minimum PHP Version](https://img.shields.io/badge/php-%3E=7.4-8892BF.svg)](https://php.net/) <img src="https://www.php.net/images/logos/new-php-logo.svg" width="35">
- [![Composer](https://img.shields.io/badge/-composer-A16F22)](https://getcomposer.org) <img src="https://getcomposer.org/img/logo-composer-transparent.png" width="25">

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
### Console Event Listener
Helper class useful when building command-line scripts:
* to print logging lines when the script execution starts and finishes
* to print some execution statistics

It implements event handlers used with Symfony components from the `symfony/console`
package.

The Listener has methods that can handle the following types of events: 
* `Symfony\Component\Console\Event\ConsoleCommandEvent` - triggered on command begin
* `Symfony\Component\Console\Event\ConsoleTerminateEvent` - triggered on command finish

The methods of the Listener are intended to be used as callbacks for Symfony's 
`EventDispatcher` from the `symfony/event-dispatcher` package.

In order to use it, you will need to create instances for its dependencies, which are:
* `ExecutionStatistics`, from the same package
* `OutputInterface`, from `Symfony\Component\Console\Output`

#### Usage
```
$app = new Symfony\Component\Console\Application('app');

$eventDispatcher = new Symfony\Component\EventDispatcher\EventDispatcher();
$consoleEventListener = new ConsoleEventListener(
    new \CommonUtils\Command\ExecutionStatistics(),
    new \Symfony\Component\Console\Output\ConsoleOutput()
);

$eventDispatcher->addListener(
    Symfony\Component\Console\ConsoleEvents::COMMAND,
     [$consoleEventListener, 'onCommandBegin']
);
$eventDispatcher->addListener(
    Symfony\Component\Console\ConsoleEvents::TERMINATE,
    [$consoleEventListener, 'onCommandFinish']
);

$app->setDispatcher($eventDispatcher);

$app->add(new MyScriptCommand());

$app->run();
```  

If you are using a DI library, you can include these instantiations into
the DI definitions file.

Example for [php-di](https://php-di.org/doc/php-definitions.html) definitions
config file:
```
return [
    Symfony\Component\Console\Output\OutputInterface::class => autowire(Symfony\Component\Console\Output\ConsoleOutput::class),
];
```

### Script Execution Statistics
Helper class that can calculate and print some script execution statistics:
* execution time (in seconds)
* memory peak usage (in MB)

#### Usage
It can be wrapped around the call that needs to be analyzed:
```
$executionStatistics = new ExecutionStatistics();

$executionStatistics->start();
... do some work ...
$executionStatistics->end();

print($this->executionStatistics->getPrintMessage());
```

It can be used in the application bootstrap file to calculate the statistics
for the whole application execution:
```
$executionStatistics = new ExecutionStatistics();
$executionStatistics->start();

require_once __DIR__ . '/vendor/autoload.php';

register_shutdown_function(function () use ($executionStatistics) {
    $executionStatistics->end();
    print($this->executionStatistics->getPrintMessage());    
});
```

Or it can be called directly with the static method:
```
$startTime = microtime(true);

require_once __DIR__ . '/vendor/autoload.php';

register_shutdown_function(function () use ($startTime) {
    ExecutionStatistics::printStats($startTime);    
});
```

### Enjoy!
