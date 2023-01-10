<?php

namespace Weebel\Console;

use Throwable;
use Weebel\Contracts\ExceptionHandlerInterface;

class ExceptionHandler implements ExceptionHandlerInterface
{
    protected bool $debug = true;

    /**
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    public function handle(Throwable $exception): void
    {
        //
    }
}
