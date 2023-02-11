<?php

namespace Weebel\Console;

use Weebel\Console\Events\CommandResolved;

class CheckOptions
{
    use HasClimate;

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

        if (array_key_exists('v', $options)) {
            $exceptionHandler = ExceptionHandler::getInstance();
            $exceptionHandler->debug = true;
        }
    }
}
