<?php

declare(strict_types=1);

namespace SnappyTest\Core;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Snappy\Core\Http;
use Snappy\Core\Emitter\ResponseEmitterInterface;
use Snappy\Core\ErrorHandler\ErrorHandlerInterface;
use Snappy\Core\Request\ServerRequestFactoryInterface;

class HttpTest extends TestCase
{
    public function testShouldDispatchRequestsAndEmitResponse(): void
    {
        self::expectNotToPerformAssertions();

        $emitter = $this->getMockBuilder(ResponseEmitterInterface::class)
            ->onlyMethods(['emit'])
            ->getMock();

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->onlyMethods(['getUri', 'getMethod', 'withAttribute'])
            ->getMockForAbstractClass();

        $request->method('withAttribute')->willReturn($request);
        $uri = $this->getMockBuilder(UriInterface::class)
            ->onlyMethods(['getPath'])
            ->getMockForAbstractClass();

        $uri->method('getPath')->willReturn('/');

        $request->method('getUri')->willReturn($uri);
        $request->method('getMethod')->willReturn('GET');

        $requestFactory = $this->getMockBuilder(ServerRequestFactoryInterface::class)
            ->onlyMethods(['create'])
            ->getMock();
        $requestFactory->method('create')->willReturn($request);

        $errorHandler = $this->getMockBuilder(ErrorHandlerInterface::class)
            ->getMock();

        $emitter->method('emit')->with($response);

        $app = new Http($emitter, $requestFactory, $errorHandler);
        $app->getRouter()->route('index', 'GET', '/', fn() => $response);
        $app->run();
    }
}