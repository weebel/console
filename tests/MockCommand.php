<?php

namespace Weebel\Console\Tests;

use Weebel\Console\Command;

class MockCommand extends Command
{
    public const NAME = "mock:command";

    public function __invoke()
    {
        echo "hello world";
    }
}
