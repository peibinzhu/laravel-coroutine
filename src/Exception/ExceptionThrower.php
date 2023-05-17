<?php

declare(strict_types=1);

namespace PeibinLaravel\Coroutine\Exception;

use Throwable;

final class ExceptionThrower
{
    public function __construct(private Throwable $throwable)
    {
    }

    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }
}
