<?php

declare(strict_types=1);

namespace SnappyApplication\Router\Resolver;

use SnappyApplication\Router\Route;

class RouteResolver
{
    private HandlerResolver $handlerResolver;
    private MiddlewareResolver $middlewareResolver;

    /**
     * @param HandlerResolver $handlerResolver
     * @param MiddlewareResolver $middlewareResolver
     */
    public function __construct(HandlerResolver $handlerResolver, MiddlewareResolver $middlewareResolver)
    {
        $this->handlerResolver = $handlerResolver;
        $this->middlewareResolver = $middlewareResolver;
    }


    public function resolve(array $route): Route
    {
        $handler = $this->handlerResolver->resolve($route['handler']);
        $middlewares = $this->middlewareResolver->resolveList($route['middlewares'] ?? []);

        return new Route(
            $route['name'],
            $route['path'],
            $handler,
            $middlewares
        );
    }

    public function resolveList(array $routes): array
    {
        foreach ($routes as &$route) {
            $route = $this->resolve($route);
        }
        return $routes;
    }
}