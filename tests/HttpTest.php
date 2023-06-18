<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Core;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Blue\Snappy\Core\Http;
use Blue\Snappy\Core\Emitter\ResponseEmitterInterface;
use Blue\Snappy\Core\ErrorHandler\ErrorHandlerInterface;
use Blue\Snappy\Core\Request\ServerRequestFactoryInterface;

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

        $app = Http::createApp($emitter, $requestFactory, $errorHandler);
        $app->getRouter()->route('index', 'GET', '/', fn() => $response);
        $app->run();
    }
}