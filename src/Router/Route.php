<?php

declare(strict_types=1);

namespace SnappyApplication\Router;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Route
{
    private string $name;
    private string $path;
    private RequestHandlerInterface $handler;

    /**
     * @var MiddlewareInterface[]
     */
    private array $middlewares;

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface $handler
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(string $name, string $path, RequestHandlerInterface $handler, array $middlewares = [])
    {
        $this->name = $name;
        $this->path = $path;
        $this->handler = $handler;
        $this->middlewares = $middlewares;
    }


    public function addMiddleware(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return RequestHandlerInterface
     */
    public function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
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
    public function getName(): string
    {
        return $this->name;
    }
}
