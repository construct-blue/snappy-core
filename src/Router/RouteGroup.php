<?php

declare(strict_types=1);

namespace SnappyApplication\Router;

use Closure;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;

final class RouteGroup
{
    private string $path;
    private string $host;
    private string $method;

    /**
     * @var Route[]
     */
    private array $routes;

    /**
     * @var MiddlewareInterface[]
     */
    private array $middlewares;

    private Closure $callback;

    private ?ErrorHandlerInterface $errorHandler;

    /**
     * @param string $path
     * @param string $host
     * @param string $method
     * @param Route[] $routes
     * @param MiddlewareInterface[] $middlewares
     * @param ErrorHandlerInterface|null $errorHandler
     */
    public function __construct(
        string $path,
        string $host = '*',
        string $method = 'GET',
        array $routes = [],
        array $middlewares = [],
        ErrorHandlerInterface $errorHandler = null
    ) {
        $this->path = $path;
        $this->host = $host;
        $this->method = $method;
        $this->routes = $routes;
        $this->middlewares = $middlewares;
        $this->errorHandler = $errorHandler;
    }


    public function addRoute(Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function getErrorHandler(): ?ErrorHandlerInterface
    {
        return $this->errorHandler;
    }

    public function setErrorHandler(?ErrorHandlerInterface $errorHandler): void
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @return MiddlewareInterface[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function resolve(): void
    {
        if (isset($this->callback)) {
            ($this->callback)($this);
        }
    }

    /**
     * @param Closure $callback
     */
    public function setCallback(Closure $callback): void
    {
        $this->callback = $callback;
    }

    public function route(string $name, string $path, RequestHandlerInterface $handler): void
    {
        $this->addRoute(new Route($name, $path, $handler));
    }
}