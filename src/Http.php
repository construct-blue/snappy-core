<?php

declare(strict_types=1);

namespace Snappy\Core;

use Psr\Http\Server\RequestHandlerInterface;
use Snappy\Core\Emitter\LaminasResponseEmitter;
use Snappy\Core\Router\Router;
use Throwable;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Snappy\Core\Emitter\ResponseEmitterInterface;
use Snappy\Core\ErrorHandler\ErrorHandlerInterface;
use Snappy\Core\Request\ServerRequestFactoryInterface;

final class Http
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

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return void
     */
    public function onGET(string $name, string $path, $handler): void
    {
        $this->getRouter()->route($name, 'get', $path, $handler);
    }

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return void
     */
    public function onPOST(string $name, string $path, $handler): void
    {
        $this->getRouter()->route($name, 'post', $path, $handler);
    }

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return void
     */
    public function onPUT(string $name, string $path, $handler): void
    {
        $this->getRouter()->route($name, 'put', $path, $handler);
    }

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return void
     */
    public function onPATCH(string $name, string $path, $handler): void
    {
        $this->getRouter()->route($name, 'patch', $path, $handler);
    }

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return void
     */
    public function onHEAD(string $name, string $path, $handler): void
    {
        $this->getRouter()->route($name, 'head', $path, $handler);
    }

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return void
     */
    public function onOPTIONS(string $name, string $path, $handler): void
    {
        $this->getRouter()->route($name, 'options', $path, $handler);
    }

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return void
     */
    public function onDELETE(string $name, string $path, $handler): void
    {
        $this->getRouter()->route($name, 'delete', $path, $handler);
    }

    public function run(): void
    {
        $this->runner->run();
    }
}