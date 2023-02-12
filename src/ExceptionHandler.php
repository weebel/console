<?php

namespace Weebel\Console;

use Throwable;
use Weebel\Contracts\ExceptionHandlerInterface;
use Weebel\Console\Concerns\HasClimate;

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
        if ($this->debug) {
            $this->yellow()->inline($exception->getFile())->red()->inline(':'.$exception->getLine())->br();

            foreach ($exception->getTrace() as $item) {
                $this->inline($item['file'])->inline(':'.$item['line'])->br();
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
