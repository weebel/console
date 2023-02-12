<?php

namespace Weebel\Console;

abstract class Command
{
    protected $options = [];

    public const NAME = "name";
    public const DESCRIPTION = "description";

    protected array $validOptions = [];
    protected array $validArguments = [];

    public function setOptions(array $options): Command
    {
        $this->options = $options;

        return $this;
    }

    public function defineOption(string $name, string $description): static
    {
        $this->options[$name] = $description;

        return $this;
    }

    public function getValidOptions(): array
    {
        return $this->validOptions;
    }

    public function getValidArguments(): array
    {
        return $this->validArguments;
    }

    public function __get(string $name)
    {
        return $this->options[$name] ?? null;
    }


    public function __set(string $name, $value): void
    {
        $this->options[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->options);
    }
}
