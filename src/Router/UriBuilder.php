<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Router;

use Psr\Http\Message\UriInterface;
use Blue\Snappy\Core\Router\Exception\UriBuilderException;

final class UriBuilder
{
    private \League\Route\Router $router;
    private UriInterface $baseUri;

    private string $routeName;

    public function __construct(\League\Route\Router $router, UriInterface $baseUri)
    {
        $this->router = $router;
        $this->baseUri = $baseUri;
    }


    public function withRouteName(string $routeName): self
    {
        $clone = clone $this;
        $clone->routeName = $routeName;
        return $clone;
    }

    /**
     * @param array<string, string> $vars
     * @return UriInterface
     * @throws UriBuilderException
     */
    public function build(array $vars = []): UriInterface
    {
        if (!isset($this->routeName)) {
            throw new UriBuilderException('Missing route name to build uri.');
        }

        $route = $this->router->getNamedRoute($this->routeName);

        $uri = $this->baseUri->withPath($route->getPath($vars));

        if (null !== $route->getScheme()) {
            $uri = $uri->withScheme($route->getScheme());
        }

        if (null !== $route->getHost()) {
            $uri = $uri->withHost($route->getHost());
        }

        if (null !== $route->getPort()) {
            $uri = $uri->withPort($route->getPort());
        }

        return $uri;
    }

    public function __debugInfo(): ?array
    {
        return [
            'baseUri' => (string)$this->baseUri
        ];
    }
}