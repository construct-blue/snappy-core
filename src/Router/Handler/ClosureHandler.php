<?php

declare(strict_types=1);

namespace SnappyApplication\Router\Handler;

use Closure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClosureHandler implements RequestHandlerInterface
{
    private Closure $closure;

    /**
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return ($this->closure)($request);
    }

}