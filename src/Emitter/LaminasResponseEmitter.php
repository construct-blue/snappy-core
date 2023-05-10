<?php

declare(strict_types=1);

namespace SnappyApplication\Emitter;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class LaminasResponseEmitter implements EmitterInterface
{
    private ResponseEmitterInterface $emitter;

    public function __construct(ResponseEmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     * @throws Throwable
     */
    public function emit(ResponseInterface $response): bool
    {
        $this->emitter->emit($response);
        return true;
    }
}