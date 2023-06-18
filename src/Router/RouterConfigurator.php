<?php

declare(strict_types=1);

namespace SnappyApplication\Router;

use SnappyApplication\Router\Resolver\MiddlewareResolver;
use SnappyApplication\Router\Resolver\RouteGroupResolver;
use SnappyApplication\Router\Resolver\RouteResolver;

class RouterConfigurator
{
    private RouteResolver $routeResolver;
    private RouteGroupResolver $routeGroupResolver;
    private MiddlewareResolver $middlewareResolver;


    public function __construct(
        RouteResolver $routeResolver,
        RouteGroupResolver $routeGroupResolver,
        MiddlewareResolver $middlewareResolver
    ) {
        $this->routeResolver = $routeResolver;
        $this->routeGroupResolver = $routeGroupResolver;
        $this->middlewareResolver = $middlewareResolver;
    }

    public function configure(Router $router, array $config): void
    {
        $middlewares = $this->middlewareResolver->resolveList($config['middlewares'] ?? []);
        $routes = $this->routeResolver->resolveList($config['routes'] ?? []);

        $globalRouteGroup = new RouteGroup('/', '*', 'GET', $routes, $middlewares);

        $router->addGroup($globalRouteGroup);

        $groups = $this->routeGroupResolver->resolveList($config['groups'] ?? []);

        foreach ($groups as $group) {
            $router->addGroup($group);
        }
    }
}