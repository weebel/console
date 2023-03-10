<?php

namespace Weebel\Console\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Container\ContainerInterface;
use Weebel\Console\Kernel;
use Weebel\Console\PreventCommandRunningException;
use Weebel\Contracts\EventDispatcher;

class KernelTest extends MockeryTestCase
{
    private \Weebel\Console\CommandContainer $commandContainer;
    private \Mockery\LegacyMockInterface|\Mockery\MockInterface|ContainerInterface $container;
    private \Mockery\LegacyMockInterface|\Mockery\MockInterface|EventDispatcher $eventDispatcher;
    private Kernel $kernel;

    protected function setUp(): void
    {
        $this->commandContainer = \Weebel\Console\CommandContainer::getInstance();
        $this->container = \Mockery::mock(ContainerInterface::class);
        $this->eventDispatcher = \Mockery::mock(EventDispatcher::class);

        $this->kernel = new Kernel($this->container, $this->commandContainer, $this->eventDispatcher);
    }

    public function testCanRunARegisteredCommandInTheContainer(): void
    {
        $this->commandContainer->register(MockCommand::class);

        $this->container->shouldReceive('get')->with(MockCommand::class)->andReturn(new MockCommand());
        $this->eventDispatcher->shouldReceive('dispatch');

        $this->expectOutputString('hello world');

        $this->kernel->runCommand(['mock:command']);
    }

    public function testUserCanRunACommandThroughTheConsoleKernelWithInputs(): void
    {
        $this->commandContainer->register(MockCommandWithInputs::class);

        $this->container->shouldReceive("get")->with(MockCommandWithInputs::class)->andReturn(new MockCommandWithInputs());
        $this->eventDispatcher->shouldReceive('dispatch');

        $this->expectOutputString("Good morning John");

        $this->kernel->runCommand(["mock:greeting", "John", "--time=morning"]);

        ob_clean();

        $this->expectOutputString("Good afternoon John");

        $this->kernel->runCommand(["mock:greeting", "John", "--time=afternoon"]);

        ob_clean();

        $this->expectOutputString("Hi John");

        $this->kernel->runCommand(["mock:greeting", "John"]);
    }

    public function testIfPreventingExceptionIsThrownFromDispatchingEventThenTheCommandWouldNotRun(): void
    {
        $this->commandContainer->register(MockCommand::class);

        $this->eventDispatcher->shouldReceive('dispatch')->withAnyArgs()->andThrows(PreventCommandRunningException::class);
        $this->container->shouldReceive('get')->with(MockCommand::class)->andReturn(new MockCommand());

        $this->expectOutputString('');

        $this->kernel->runCommand(['mock:command']);
    }
}
