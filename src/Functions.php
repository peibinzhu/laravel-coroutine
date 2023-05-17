<?php

declare(strict_types=1);

namespace PeibinLaravel\Coroutine;

use Closure;
use Illuminate\Container\Container;

/**
 * @param callable[] $callables
 * @param int        $concurrent if $concurrent is equal to 0, that means unlimited
 */
function parallel(array $callables, int $concurrent = 0): array
{
    $parallel = new Parallel($concurrent);
    foreach ($callables as $key => $callable) {
        $parallel->add($callable, $key);
    }
    return $parallel->wait();
}

function wait(Closure $closure, ?float $timeout = null)
{
    $container = Container::getInstance();
    if ($container->has(Waiter::class)) {
        $waiter = $container->get(Waiter::class);
        return $waiter->wait($closure, $timeout);
    }
    return (new Waiter())->wait($closure, $timeout);
}

function co(callable $callable): bool | int
{
    $id = Coroutine::create($callable);
    return $id > 0 ? $id : false;
}
