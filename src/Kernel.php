<?php

namespace Weebel\Console;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Weebel\Console\Events\CommandResolved;
use Weebel\Contracts\Bootable;
use Weebel\Contracts\CommandContainer;

class Kernel implements Bootable
{
    public function __construct(
        protected ContainerInterface $container,
        protected CommandContainer   $commandContainer,
        protected EventDispatcherInterface    $eventDispatcher
    ) {
    }

    public function boot(): void
    {
        $this->runCommand(array_slice($_SERVER['argv'], 1));
    }

    /**
     * The input should be something like this: ["test:command","argument1", "argument2", "--option1=test", "-t"]
     *
     * @throws ConsoleException
     */
    public function runCommand(array $commandArray)
    {
        $command = $this->resolveCommand(array_shift($commandArray));

        if (!is_callable($command)) {
            throw new ConsoleException(sprintf("Command %s should be callable and have an invoke method", get_class($command)));
        }

        [$arguments, $options] = $this->resolveArgumentsAndOptions($commandArray);

        $command->setOptions($options);

        try {
            $this->eventDispatcher->dispatch(new CommandResolved($command, $arguments, $options));
        } catch (PreventCommandRunningException $preventCommandRunning) {
            return 0;
        }


        return ($command)(...$arguments);
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws ConsoleException
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function resolveCommand(string $commandName): Command
    {
        if (!$this->commandContainer->has($commandName)) {
            throw new ConsoleException("$commandName is not registered as a command");
        }

        return $this->container->get($this->commandContainer->get($commandName));
    }

    protected function resolveArgumentsAndOptions(array $commandArray): array
    {
        $arguments = [];
        $options = [];

        foreach ($commandArray as $item) {
            if (preg_match("/--(.*)=(.*)/", $item, $matches)) {
                $options[$matches[1]] = $matches[2];
            } elseif (preg_match("/--(.*)/", $item, $matches)) {
                $options[$matches[1]] = true;
            } elseif (preg_match("/-(.*)/", $item, $matches)) {
                $options[$matches[1]] = true;
            } else {
                $arguments[] = $item;
            }
        }

        return [$arguments, $options];
    }
}
