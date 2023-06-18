<?php

declare(strict_types=1);

namespace SnappyApplication\Router\Resolver;

use SnappyApplication\Router\RouteGroup;

class RouteGroupResolver
{
    private RouteResolver $routeResolver;
    private MiddlewareResolver $middlewareResolver;
    private ErrorHandlerResolver $errorHandlerResolver;

    /**
     * @param RouteResolver $routeResolver
     * @param MiddlewareResolver $middlewareResolver
     * @param ErrorHandlerResolver $errorHandlerResolver
     */
    public function __construct(
        RouteResolver $routeResolver,
        MiddlewareResolver $middlewareResolver,
        ErrorHandlerResolver $errorHandlerResolver
    ) {
        $this->routeResolver = $routeResolver;
        $this->middlewareResolver = $middlewareResolver;
        $this->errorHandlerResolver = $errorHandlerResolver;
    }


    public function resolve(array $routeGroup): RouteGroup
    {
        $routes = $this->routeResolver->resolveList($routeGroup['routes'] ?? []);
        $middlewares = $this->middlewareResolver->resolveList($routeGroup['middlewares'] ?? []);

        $errorHandler = isset($routeGroup['errorHandler'])
            ? $this->errorHandlerResolver->resolve($routeGroup['errorHandler'])
            : null;

        return new RouteGroup(
            $routeGroup['path'],
            $routeGroup['host'] ?? '*',
            $routeGroup['method'] ?? 'GET',
            $routes,
            $middlewares,
            $errorHandler
        );
    }

    public function resolveList(array $routeGroups): array
    {
        foreach ($routeGroups as &$routeGroup) {
            $routeGroup = $this->resolve($routeGroup);
        }
        return $routeGroup;
    }
}