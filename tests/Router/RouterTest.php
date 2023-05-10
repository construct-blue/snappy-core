<?php

declare(strict_types=1);

namespace Router;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;
use SnappyApplication\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testShouldUseErrorHandlerForNotFound(): void
    {
        $errorHandler = $this->getMockBuilder(ErrorHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $response = new Response('test');
        $errorHandler->method('handle')->willReturn($response);


        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->onlyMethods(['getUri'])
            ->getMockForAbstractClass();

        $uri = $this->getMockBuilder(UriInterface::class)->getMock();

        $request->method('getUri')->willReturn($uri);

        $router = new Router($errorHandler);

        $this->assertSame($response, $router->handle($request));
    }
}
