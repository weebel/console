<?php

namespace Weebel\Console;

use Psr\Container\ContainerInterface;
use Weebel\Console\Events\CommandResolved;

class CheckOptions
{
    use HasClimate;

    public function __construct(protected ContainerInterface $container)
    {
    }

    public function __invoke(CommandResolved $event)
    {
        $options = $event->getOptions();
        if (array_key_exists('help', $options)) {
            $this->green('Description:');
            $this->tab(1)->out($event->getCommand()::DESCRIPTION)->br();

            $this->green('Usage:');
            $this->tab(1)->out($event->getCommand()::NAME)->br();

            $this->yellow('Options:');
            foreach ($event->getCommand()->getValidOptions() as $key => $value) {
                $this->tab()->green()->inline(sprintf('--%s', $key));
                $this->tab(2)->inline($value)->br();
            }

            throw new PreventCommandRunningException();
        }

        if (array_key_exists('v', $options)){
            $exceptionHandler = $this->getExceptionHandler();
            $exceptionHandler->debug = true;

        }
    }

    /**
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getExceptionHandler(): ExceptionHandler
    {
        return $this->container->get(ExceptionHandler::class);
    }
}
