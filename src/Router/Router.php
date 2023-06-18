<?php

declare(strict_types=1);

namespace SnappyApplication\Router;

use Closure;
use League\Route\RouteCollectionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;

class Router implements RequestHandlerInterface
{

    private ErrorHandlerInterface $errorHandler;
    /**
     * @var RouteGroup[][]
     */
    private array $groups = [];

    public function __construct(ErrorHandlerInterface $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function addGroup(RouteGroup $group): self
    {
        $this->groups[$group->getHost()][] = $group;
        return $this;
    }

    private function resolveGroup(RouteGroup $group, RouteCollectionInterface $collection): void
    {
        $group->resolve();
        foreach ($group->getRoutes() as $route) {
            $map = $collection->map(
                $group->getMethod(),
                $route->getPath(),
                [$route->getHandler(), 'handle']
            );
            $map->middlewares($route->getMiddlewares());
            $map->setName($route->getName());
            if ($group->getHost() !== '*') {
                $map->setHost($group->getHost());
            }
        }
    }

    /**
     * @param RouteGroup[] $groups
     * @return \League\Route\Router
     */
    private function createRouter(array $groups): \League\Route\Router
    {
        $router = new \League\Route\Router();
        $router->setStrategy(new ErrorHandlerStrategy($this->errorHandler));
        foreach ($groups as $group) {
            $map = $router->group(
                $group->getPath(),
                fn(RouteCollectionInterface $collection) => $this->resolveGroup($group, $collection)
            );
            if ($group->getErrorHandler()) {
                $map->setStrategy(new ErrorHandlerStrategy($group->getErrorHandler()));
            }
            $map->middlewares($group->getMiddlewares());
            if ($group->getHost() !== '*') {
                $map->setHost($group->getHost());
            }
        }
        return $router;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $host = $request->getUri()->getHost();

        $groups = $this->groups['*'];
        if (isset($this->groups[$host])) {
            $groups = $this->groups[$host];
        }
        
        $router = $this->createRouter($groups);

        return $router->handle(
            $request->withAttribute(UriBuilder::class, new UriBuilder($router, $request->getUri()))
        );
    }

    /**
     * @param string $name
     * @param string $path
     * @param RequestHandlerInterface $handler
     * @param ErrorHandlerInterface|null $errorHandler
     * @return void
     */
    public function route(
        string $name,
        string $path,
        RequestHandlerInterface $handler,
        ErrorHandlerInterface $errorHandler = null
    ): void {
        $group = new RouteGroup('/');
        $group->route($name, $path, $handler);
        $group->setErrorHandler($errorHandler);
        $this->addGroup($group);
    }

    /**
     * @param string $host
     * @param Closure $callback
     * @param ErrorHandlerInterface|null $errorHandler
     * @return void
     */
    public function host(string $host, Closure $callback, ErrorHandlerInterface $errorHandler = null): void
    {
        $group = new RouteGroup('/', $host);
        $group->setCallback($callback);
        $group->setErrorHandler($errorHandler);
        $this->addGroup($group);
    }

    /**
     * @param string $path
     * @param Closure $callback
     * @param ErrorHandlerInterface|null $errorHandler
     * @return void
     */
    public function path(string $path, Closure $callback, ErrorHandlerInterface $errorHandler = null): void
    {
        $group = new RouteGroup($path);
        $group->setCallback($callback);
        $group->setErrorHandler($errorHandler);
        $this->addGroup($group);
    }
}