<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Core\ErrorHandler;

use Exception;
use League\Route\Http\Exception\NotFoundException;
use Blue\Snappy\Core\ErrorHandler\TextErrorHandler;
use PHPUnit\Framework\TestCase;

class TextErrorHandlerTest extends TestCase
{
    public function testShouldBuildJsonResponseFromRouterHttpException(): void
    {
        $handler = new TextErrorHandler();
        $exception = new NotFoundException();
        $response = $handler->handle($exception);
        $this->assertEquals($exception->getStatusCode(), $response->getStatusCode());
        $this->assertEquals($exception->getMessage(), $response->getReasonPhrase());
        $this->assertEquals(
            'Not Found',
            $response->getBody()->getContents()
        );
    }

    public function testShouldReturnInternalServerErrorForAllUnknownExceptions(): void
    {
        $handler = new TextErrorHandler();
        $exception = new Exception('other', 123);
        $response = $handler->handle($exception);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Internal Server Error', $response->getReasonPhrase());
        $this->assertEquals(
            'Internal Server Error',
            $response->getBody()->getContents()
        );
    }
}
