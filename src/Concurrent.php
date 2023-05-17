<?php

declare(strict_types=1);

namespace PeibinLaravel\Coroutine;

use Illuminate\Container\Container;
use PeibinLaravel\Contracts\ExceptionFormatter\FormatterInterface;
use PeibinLaravel\Contracts\StdoutLoggerInterface;
use PeibinLaravel\Coroutine\Exception\InvalidArgumentException;
use PeibinLaravel\Engine\Channel;
use Throwable;

/**
 * @method bool isFull()
 * @method bool isEmpty()
 */
class Concurrent
{
    protected Channel $channel;

    public function __construct(protected int $limit)
    {
        $this->channel = new Channel($limit);
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, ['isFull', 'isEmpty'])) {
            return $this->channel->{$name}(...$arguments);
        }

        throw new InvalidArgumentException(sprintf('The method %s is not supported.', $name));
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function length(): int
    {
        return $this->channel->getLength();
    }

    public function getLength(): int
    {
        return $this->channel->getLength();
    }

    public function getRunningCoroutineCount(): int
    {
        return $this->getLength();
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function create(callable $callable): void
    {
        $this->channel->push(true);

        Coroutine::create(function () use ($callable) {
            try {
                $callable();
            } catch (Throwable $exception) {
                $container = Container::getInstance();
                if ($container->has(StdoutLoggerInterface::class)) {
                    $logger = $container->get(StdoutLoggerInterface::class);
                    if ($container->has(FormatterInterface::class)) {
                        $formatter = $container->get(FormatterInterface::class);
                        $logger->error($formatter->format($exception));
                    } else {
                        $logger->error((string)$exception);
                    }
                }
            } finally {
                $this->channel->pop();
            }
        });
    }
}
