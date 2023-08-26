<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Environment;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EnvironmentMiddleware implements MiddlewareInterface
{
    public function __construct(private Environemnt $environemnt)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request->withAttribute(Environemnt::class, $this->environemnt));
    }
}