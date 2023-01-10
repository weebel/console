<?php

namespace Weebel\Console;

use Weebel\Contracts\Bootable;
use Weebel\Contracts\Container;

class ConsoleProvider implements Bootable
{
    public function __construct(protected Container $container)
    {
    }

    public function boot(): void
    {
        $this->container->alias(\Weebel\Contracts\CommandContainer::class, CommandContainer::class);
    }
}