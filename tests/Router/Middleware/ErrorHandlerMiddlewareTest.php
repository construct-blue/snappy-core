<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Core\Router\Middleware;

use Exception;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Blue\Snappy\Core\ErrorHandler\ErrorHandlerInterface;
use Blue\Snappy\Core\Router\Middleware\ErrorHandlerMiddleware;
use PHPUnit\Framework\TestCase;

class ErrorHandlerMiddlewareTest extends TestCase
{
    public function testShouldCatchExceptionsAndCallErrorHandler(): void
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();

        $errorHandler = $this
            ->getMockBuilder(ErrorHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $exception = new Exception('test');
        $response = new Response();
        $errorHandler
            ->expects(self::once())
            ->method('handle')
            ->with($exception, $request)
            ->willReturn($response);

        $handler = $this
            ->getMockBuilder(RequestHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();

        $handler->method('handle')->willThrowException($exception);

        $middleware = new ErrorHandlerMiddleware($errorHandler);

        $this->assertEquals($response, $middleware->process($request, $handler));
    }
}
