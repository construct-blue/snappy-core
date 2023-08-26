<?php

declare(strict_types=1);

namespace Blue\Snappy\Core;

use Blue\Snappy\Core\Assets\AssetsLoader;
use Blue\Snappy\Core\Assets\AssetsLoaderMiddleware;
use Blue\Snappy\Core\Environment\Environemnt;
use Blue\Snappy\Core\Environment\EnvironmentMiddleware;
use Blue\Snappy\Renderer\Renderer;
use Exception;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\NotFoundException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Blue\Snappy\Core\Emitter\LaminasResponseEmitter;
use Blue\Snappy\Core\Emitter\SapiResponseEmitter;
use Blue\Snappy\Core\ErrorHandler\HtmlErrorHandler;
use Blue\Snappy\Core\ErrorHandler\JsonErrorHandler;
use Blue\Snappy\Core\Request\SapiServerRequestFactory;
use Blue\Snappy\Core\Router\Router;
use Throwable;
use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\RequestHandlerRunnerInterface;
use Blue\Snappy\Core\Emitter\ResponseEmitterInterface;
use Blue\Snappy\Core\ErrorHandler\ErrorHandlerInterface;
use Blue\Snappy\Core\Request\ServerRequestFactoryInterface;

final class Http
{
    private RequestHandlerRunnerInterface $runner;
    private Router $router;
    private Environemnt $environemnt;

    private bool $running = false;

    private function __construct(
        ResponseEmitterInterface $emitter,
        ServerRequestFactoryInterface $requestFactory,
        ErrorHandlerInterface $errorHandler
    ) {
        $this->environemnt = new Environemnt();
        $this->runner = new RequestHandlerRunner(
            $this->router = new Router($errorHandler),
            new LaminasResponseEmitter($emitter),
            fn() => $requestFactory->create(),
            fn(Throwable $throwable) => $errorHandler->handle($throwable)
        );
        $this->addMiddleware(new EnvironmentMiddleware($this->environemnt));
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

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->router->middleware($middleware);
    }

    public function run(): void
    {
        chdir($this->environemnt->getDirectory());
        $this->running = true;
        $this->runner->run();
    }

    public function __destruct()
    {
        if (!$this->running) {
            $this->run();
        }
    }

    public function initAssets(string $assetsManifest = 'assets-manifest.json'): void
    {
        $this->addMiddleware(new AssetsLoaderMiddleware(new AssetsLoader($assetsManifest)));
    }

    public static function createApp(
        ResponseEmitterInterface $emitter,
        ServerRequestFactoryInterface $requestFactory,
        ErrorHandlerInterface $errorHandler
    ): Http {
        return new Http($emitter, $requestFactory, $errorHandler);
    }

    public static function jsonApi(
        ResponseEmitterInterface $emitter = null,
        ServerRequestFactoryInterface $requestFactory = null
    ): self {
        return Http::createApp(
            $emitter ?? new SapiResponseEmitter(),
            $requestFactory ?? new SapiServerRequestFactory(),
            new JsonErrorHandler()
        );
    }

    public static function htmlApp(
        ResponseEmitterInterface $emitter = null,
        ServerRequestFactoryInterface $requestFactory = null
    ): self {
        return Http::createApp(
            $emitter ?? new SapiResponseEmitter(),
            $requestFactory ?? new SapiServerRequestFactory(),
            new HtmlErrorHandler(new Renderer())
        );
    }

    /**
     * @param string $message
     * @param Exception|null $previous
     * @param int $code
     * @return never
     * @throws BadRequestException
     */
    public static function throwBadRequest(string $message, ?Exception $previous = null, int $code = 0): never
    {
        throw new BadRequestException($message, $previous, $code);
    }

    /**
     * @param string $message
     * @param Exception|null $previous
     * @param int $code
     * @return never
     * @throws NotFoundException
     */
    public static function throwNotFound(string $message, ?Exception $previous = null, int $code = 0): never
    {
        throw new NotFoundException($message, $previous, $code);
    }
}