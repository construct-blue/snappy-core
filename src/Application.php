<?php

declare(strict_types=1);

namespace SnappyApplication;

use SnappyApplication\Emitter\LaminasResponseEmitter;
use SnappyApplication\Router\Router;
use Throwable;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use SnappyApplication\Emitter\ResponseEmitterInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;
use SnappyApplication\Request\ServerRequestFactoryInterface;

final class Application
{
    private RequestHandlerRunnerInterface $runner;
    private Router $router;

    public function __construct(
        ResponseEmitterInterface $emitter,
        ServerRequestFactoryInterface $requestFactory,
        ErrorHandlerInterface $errorHandler
    ) {
        $this->runner = new RequestHandlerRunner(
            $this->router = new Router($errorHandler),
            new LaminasResponseEmitter($emitter),
            fn() => $requestFactory->create(),
            fn(Throwable $throwable) => $errorHandler->handle($throwable)
        );
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        $this->runner->run();
    }
}