<?php

namespace Weebel\Console\Events;

use Weebel\Console\Command;

class CommandResolved
{
    protected Command $command;

    protected array $options;

    protected array $arguments;

    public function __construct(Command $command, array $arguments, array $options)
    {
        $this->command = $command;
        $this->arguments = $arguments;
        $this->options = $options;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}
