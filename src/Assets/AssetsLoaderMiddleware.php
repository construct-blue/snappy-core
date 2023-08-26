<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Assets;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AssetsLoaderMiddleware implements MiddlewareInterface
{
    public function __construct(private AssetsLoader $assetsLoader)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle(
            $request->withAttribute(AssetsLoader::class, $this->assetsLoader)
        );
    }
}