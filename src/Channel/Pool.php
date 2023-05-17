<?php

declare(strict_types=1);

namespace PeibinLaravel\Coroutine\Channel;

use PeibinLaravel\Engine\Channel;
use SplQueue;

class Pool extends SplQueue
{
    protected static ?Pool $instance = null;

    public static function getInstance(): self
    {
        return static::$instance ??= new self();
    }

    public function get(): Channel
    {
        return $this->isEmpty() ? new Channel(1) : $this->pop();
    }

    public function release(Channel $channel)
    {
        $channel->errCode = 0;
        $this->push($channel);
    }
}
