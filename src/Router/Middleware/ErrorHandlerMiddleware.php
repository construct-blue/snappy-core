<?php

declare(strict_types=1);

namespace SnappyApplication\Router\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;
use Throwable;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    private ErrorHandlerInterface $errorHandler;

    /**
     * @param ErrorHandlerInterface $errorHandler
     */
    public function __construct(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $throwable) {
            return $this->errorHandler->handle($throwable, $request);
        }
    }
}