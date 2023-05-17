<?php

declare(strict_types=1);

namespace PeibinLaravel\Coroutine\Exception;

use RuntimeException;

class ParallelExecutionException extends RuntimeException
{
    protected array $results = [];

    protected array $throwables = [];

    public function getResults(): array
    {
        return $this->results;
    }

    public function setResults(array $results)
    {
        $this->results = $results;
    }

    public function getThrowables(): array
    {
        return $this->throwables;
    }

    public function setThrowables(array $throwables)
    {
        return $this->throwables = $throwables;
    }
}
