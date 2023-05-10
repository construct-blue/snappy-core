<?php

declare(strict_types=1);

namespace SnappyApplication\Router;

use League\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;

class Router implements RequestHandlerInterface
{
    private \League\Route\Router $router;

    public function __construct(ErrorHandlerInterface $errorHandler)
    {
        $this->router = new \League\Route\Router();
        $this->router->setStrategy(new ApplicationStrategy($errorHandler));
    }

    public function pipe(MiddlewareInterface $middleware): self
    {
        $this->router->middleware($middleware);
        return $this;
    }

    public function route(string $method, string $path, $handler): Route
    {
        if ($handler instanceof RequestHandlerInterface) {
            $handler = [$handler, 'handle'];
        }

        return $this->router->map($method, $path, $handler);
    }

    public function GET(string $path, $handler): Route
    {
        return $this->route('GET', $path, $handler);
    }

    public function POST(string $path, $handler): Route
    {
        return $this->route('POST', $path, $handler);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->handle($request);
    }
}