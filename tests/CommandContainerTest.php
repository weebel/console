<?php

namespace Weebel\Console\Tests;

use PHPUnit\Framework\TestCase;
use Weebel\Console\ConsoleException;

class CommandContainerTest extends TestCase
{
    public function testCanRegisterACommandAndResolveItByTheCommandName(): void
    {
        $container = \Weebel\Console\CommandContainer::getInstance();

        $container->register(MockCommand::class);

        $command = $container->get("mock:command");

        $this->assertEquals(MockCommand::class, $command);
        $this->assertTrue($container->has("mock:command"));
    }

    public function testThrowsExceptionIfTheCommandDoesnNotExist(): void
    {
        $container = \Weebel\Console\CommandContainer::getInstance();

        $this->expectException(ConsoleException::class);

        $container->get("mock:unregistered");
    }

    public function testThrowsExceptionIfTheNewCommandDoesnHaveAssociatedNameMethod(): void
    {
        $container = \Weebel\Console\CommandContainer::getInstance();

        $this->expectException(ConsoleException::class);

        $container->register(InvalidCommand::class);
    }
}

class InvalidCommand
{
    public function __invoke()
    {
        echo "hello world";
    }
}
