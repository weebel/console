<?php

namespace Weebel\Console\StandAlone;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Weebel\Console\CommandContainer;
use Weebel\Console\ExceptionHandler;
use Weebel\Console\Kernel;

class ConsoleKernelBooter
{
    public static function start(ContainerInterface $container, ?EventDispatcherInterface $eventDispatcher = null): void
    {
        if (!$eventDispatcher) {
            $eventDispatcher = new InternalDispatcher($container);
        }
        $kernel = new Kernel($container, CommandContainer::getInstance(), $eventDispatcher);
        try {
            $kernel->boot();
        } catch (\Throwable $exception) {
            $handler = ExceptionHandler::getInstance();
            $handler->handle($exception);
        }
    }
}
