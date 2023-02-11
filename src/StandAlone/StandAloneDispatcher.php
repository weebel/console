<?php

namespace Weebel\Console\StandAlone;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Weebel\Console\CheckOptions;
use Weebel\Console\Events\CommandResolved;

class StandAloneDispatcher implements EventDispatcherInterface
{
    protected $listeners=[
        CommandResolved::class => [CheckOptions::class]
    ];

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function dispatch(object $event)
    {
        if (!array_key_exists(get_class($event), $this->listeners)) {
            return $event;
        }

        foreach ($this->listeners[get_class($event)] as $listener) {
            $listener = $this->container->get($listener);
            $listener($event);
        }

        return $event;
    }
}
