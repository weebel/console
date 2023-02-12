# Weebel Console
A simple and flexible console package for PHP that lets you build command-line applications with ease.

## Installation
The easiest way to install the package is through composer:
```shell
composer require weebel/console
```

## Usage
To utilize the console kernel, you must have a container class that implements the `Psr\Container\ContainerInterface` interface. A popular example of this is the Laravel container. Here is a simple usage example of this package using the Laravel container in the main bootstrap executable file in the project (you may name the file as console in the bin directory of your project):

```php 
#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

$commandContainer = \Weebel\Console\CommandContainer::getInstance();

$commandContainer->register(ExampleCommand::class);

$container = \Illuminate\Container\Container::getInstance();

 \Weebel\Console\StandAlone\ConsoleKernelBooter::start($container);
 ```
In the example above, we first include the autoload file to bring in all the necessary dependencies. Then, we retrieve the CommandContainer instance, and register the ExampleCommand class with it. Finally, we create an instance of the container and pass it to the `ConsoleKernelBooter` class to start the console application.

### Creating Commands
To create a command, extend the `Weebel\Console\Command` class and add a unique name constant and a description constant for the command. You can also specify the valid options for the command by defining the $validOptions property as an array.
Here's an example of a greeting command:
```php
<?php
namespace App;

use Weebel\Console\Command;
use Weebel\Console\Concerns\HasClimate;

class GreetingCommand extends Command
{
    use HasClimate;

    public const NAME = 'app:greeting';
    public const DESCRIPTION = 'Saying good morning in some languages';

    protected array $validOptions = [
        'lang'  => 'Language for greeting | Optional'
    ];

    public function __invoke(string $name)
    {
        switch ($this->lang) {
            case 'en':
                $greeting = "Good morning";
                break;
            case 'nl':
                $greeting = "Goede morgen";
                break;
            case 'de':
                $greeting = "Guten Tag";
                break;
            default:
                $greeting = "Good morning";
                break;
        }
        
        $this->out("$greeting $name");
    }
}

```
After registering your command, you can execute it using the following syntax in the terminal:
```shell
bin/console app:greeting John --lang=eng 
```
### Customizing the Console Output

The package provides a `HasClimate` trait, which can be used to customize the console output. You can use this trait in your commands to add colors, formatting and other customizations to the output.
Here are a few examples of how you can use the HasClimate trait to customize the console output:

- To add colors:
```php 
$this->red('This text will be displayed in red');
$this->yellow('This text will be displayed in yellow');
```

- To format text:
```php 
$this->bold('This text will be displayed in bold');
$this->italic('This text will be displayed in italic');
$this->underline('This text will be displayed underlined'); 
```

- To display messages with tabs
```php 
$this->tab(2)->out('This message will be displayed with two tabs');
```

- To display messages with line breaks

```php 
$this->out('This message will be displayed')->br()->out('on a new line');
```

With the `HasClimate` trait, you have full control over the way the console output is displayed, allowing you to create visually appealing and easy-to-read outputs.

## Extending the functionality
The package provides a way to extend its functionality by allowing you to create listeners for the events it triggers. One such event is the `CommandResolved` event which is triggered when a command has been resolved from the input. This event provides information about the resolved command and its options.
- In order to use this functionality you need to pass your EventDispatcher implementation to the kernel booter. Your event dispatcher should implement `Psr\EventDispatcher\EventDispatcherInterface`.

Here is an example of using the popular Symfony event dispatcher in the console kernel:
```php 
#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Weebel\Console\CommandContainer;
use Weebel\Console\StandAlone\ConsoleKernelBooter;
use Illuminate\Container\Container;

$commandContainer = CommandContainer::getInstance();
$commandContainer->register(GreetingCommand::class);

$container = Container::getInstance();

$dispatcher = new EventDispatcher();

ConsoleKernelBooter::start($container, $dispatcher);

```

## License
Weebel Console is open-source software licensed under the MIT license.