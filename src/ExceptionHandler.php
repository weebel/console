<?php

namespace Weebel\Console;

use Throwable;
use Weebel\Contracts\ExceptionHandlerInterface;

class ExceptionHandler implements ExceptionHandlerInterface
{
    use HasClimate;

    public bool $debug = false;

    private static ?ExceptionHandler $instance = null;

    /**
     * @param bool $debug
     */
    protected function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    public function handle(Throwable $exception): void
    {
        $this->backgroundRed($exception->getMessage());
        if ($this->debug) {
            foreach ($exception->getTrace() as $line) {
                $this->out($line);
            }
        }
    }

    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
