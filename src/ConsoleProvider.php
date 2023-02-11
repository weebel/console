<?php

namespace Weebel\Console;

use Weebel\Console\Events\CommandResolved;
use Weebel\Contracts\Bootable;
use Weebel\Contracts\Container;
use Weebel\Contracts\EventDispatcher;

class ConsoleProvider implements Bootable
{
    public function __construct(protected Container $container, protected EventDispatcher $eventDispatcher)
    {
    }

    public function boot(): void
    {
        $this->container->alias(\Weebel\Contracts\CommandContainer::class, CommandContainer::class);
        $this->container->set(CommandContainer::class, CommandContainer::getInstance());
        $this->container->set(ExceptionHandler::class, ExceptionHandler::getInstance());
        $this->eventDispatcher->addListener(CommandResolved::class, CheckOptions::class);
    }
}
