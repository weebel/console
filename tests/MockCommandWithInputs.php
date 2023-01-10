<?php

namespace Weebel\Console\Tests;

use Weebel\Console\Command;

class MockCommandWithInputs extends Command
{
    public const NAME = "mock:greeting";

    public function __invoke($name)
    {
        if ($this->time ==="morning") {
            $greeting = "Good morning";
        } elseif ($this->time ==="afternoon") {
            $greeting = "Good afternoon";
        } else {
            $greeting = "Hi";
        }

        $greeting .= " $name";

        echo $greeting;
    }
}
