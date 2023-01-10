<?php

namespace Weebel\Console;

use Weebel\Contracts\CommandContainer as CommandContainerInterface;

class CommandContainer implements CommandContainerInterface
{
    private static ?CommandContainer $instance = null;

    protected array $registered = [];

    protected function __construct()
    {
    }

    public function register(string $commandClassName): void
    {
        try {
            $this->registered[$commandClassName::name()] = $commandClassName;
        } catch (\Throwable $exception) {
            throw new ConsoleException(
                sprintf(
                    'Command %s should have static name() method returning the command name as string. %s',
                    $commandClassName,
                    $exception->getMessage()
                )
            );
        }
    }

    public function getRegistered(): array
    {
        return $this->registered;
    }


    public function has(string $commandName): bool
    {
        return array_key_exists($commandName, $this->registered);
    }

    /**
     * @throws ConsoleException
     */
    public function get(string $commandName): string
    {
        if (!$this->has($commandName)) {
            throw new ConsoleException(sprintf('Command %s is not registered in the command container.', $commandName));
        }
        return $this->registered[$commandName];
    }

    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
