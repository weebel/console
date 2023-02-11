<?php

namespace Weebel\Console\Tests;

use Weebel\Console\Command;

class MockCommand extends Command
{
    public const NAME = "mock:command";

    public const DESCRIPTION = 'mock command to be used for testing';

    protected array $validOptions = [
        'foo' => 'Foo option | string',
        'bar' => 'Bar option | string',
    ];

    public function __invoke()
    {
        echo "hello world";
    }
}
