<?php

declare(strict_types=1);

namespace SnappyApplication\Emitter;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface ResponseEmitterInterface
{
    /**
     * @param ResponseInterface $response
     * @throws Throwable
     * @return void
     */
    public function emit(ResponseInterface $response): void;
}