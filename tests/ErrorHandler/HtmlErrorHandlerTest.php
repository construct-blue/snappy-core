<?php

declare(strict_types=1);

namespace SnappyTest\Core\ErrorHandler;

use Exception;
use League\Route\Http\Exception\NotFoundException;
use Snappy\Core\ErrorHandler\HtmlErrorHandler;
use PHPUnit\Framework\TestCase;
use SnappyRenderer\Renderer;

class HtmlErrorHandlerTest extends TestCase
{
    public function testShouldBuildJsonResponseFromRouterHttpException(): void
    {
        $handler = new HtmlErrorHandler(new Renderer());
        $exception = new NotFoundException();
        $response = $handler->handle($exception);
        $this->assertEquals($exception->getStatusCode(), $response->getStatusCode());
        $this->assertEquals($exception->getMessage(), $response->getReasonPhrase());
        $this->assertStringContainsString(
            'Not Found',
            $response->getBody()->getContents()
        );
    }

    public function testShouldReturnInternalServerErrorForAllUnknownExceptions(): void
    {
        $handler = new HtmlErrorHandler(new Renderer());
        $exception = new Exception('other', 123);
        $response = $handler->handle($exception);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Internal Server Error', $response->getReasonPhrase());
        $this->assertStringNotContainsString('other', $response->getBody()->getContents());
    }
}
