<?php

declare(strict_types=1);

namespace SnappyApplication\Router\Resolver;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class MiddlewareResolver
{

    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(string $middleware): MiddlewareInterface
    {
        return $this->container->get($middleware);
    }

    public function resolveList(array $middlewares): array
    {
        foreach ($middlewares as &$middleware) {
            $middleware = $this->resolve($middleware);
        }
        return $middlewares;
    }
}