<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Emitter;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ResponseInterface;

class SapiResponseEmitter implements ResponseEmitterInterface
{
    private EmitterInterface $emitter;

    public function __construct()
    {
        $this->emitter = new SapiEmitter();
    }

    public function emit(ResponseInterface $response): void
    {
        $this->emitter->emit($response);
    }
}