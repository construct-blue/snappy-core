<?php

declare(strict_types=1);

namespace SnappyApplication\Router;

use League\Route\Http\Exception\MethodNotAllowedException;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Server\MiddlewareInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;
use SnappyApplication\Router\Middleware\ErrorHandlerMiddleware;
use SnappyApplication\Router\Middleware\MethodNotAllowedMiddleware;
use SnappyApplication\Router\Middleware\NotFoundMiddleware;

class ApplicationStrategy extends \League\Route\Strategy\ApplicationStrategy
{
    private ErrorHandlerInterface $errorHandler;

    public function __construct(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function getMethodNotAllowedDecorator(MethodNotAllowedException $exception): MiddlewareInterface
    {
        return new MethodNotAllowedMiddleware($this->errorHandler, $exception);
    }

    public function getNotFoundDecorator(NotFoundException $exception): MiddlewareInterface
    {
        return new NotFoundMiddleware($this->errorHandler, $exception);
    }

    public function getThrowableHandler(): MiddlewareInterface
    {
        return new ErrorHandlerMiddleware($this->errorHandler);
    }
}