<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Router;

use League\Route\RouteConditionHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Blue\Snappy\Core\ErrorHandler\ErrorHandlerInterface;

class Router implements RequestHandlerInterface
{
    private \League\Route\Router $router;

    public function __construct(ErrorHandlerInterface $errorHandler)
    {
        $this->router = new \League\Route\Router();
        $this->router->setStrategy(new ErrorHandlerStrategy($errorHandler));
    }

    /**
     * @param string $name
     * @param string $method
     * @param string $path
     * @param RequestHandlerInterface|callable $handler
     * @return RouteConditionHandlerInterface
     */
    public function route(string $name, string $method, string $path, $handler): RouteConditionHandlerInterface
    {
        if ($handler instanceof RequestHandlerInterface) {
            $handler = [$handler, 'handle'];
        }
        return $this->router->map(strtoupper($method), $path, $handler)->setName($name);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->handle(
            $request->withAttribute(UriBuilder::class, new UriBuilder($this->router, $request->getUri()))
        );
    }
}