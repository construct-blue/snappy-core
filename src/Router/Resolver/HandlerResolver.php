<?php

declare(strict_types=1);

namespace SnappyApplication\Router\Resolver;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HandlerResolver
{
    private ContainerInterface $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function resolve(string $handler): RequestHandlerInterface
    {
        return $this->container->get($handler);
    }
}