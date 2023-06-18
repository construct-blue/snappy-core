<?php

declare(strict_types=1);

namespace SnappyApplication\Router\Resolver;

use Psr\Container\ContainerInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;

class ErrorHandlerResolver
{
    private ContainerInterface $container;

    public function resolve(string $errorHandler): ErrorHandlerInterface
    {
        return $this->container->get($errorHandler);
    }
}