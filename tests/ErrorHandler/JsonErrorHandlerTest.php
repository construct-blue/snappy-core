<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Core\ErrorHandler;

use Exception;
use Laminas\Diactoros\Response;
use League\Route\Http\Exception\NotFoundException;
use Blue\Snappy\Core\ErrorHandler\JsonErrorHandler;
use PHPUnit\Framework\TestCase;

class JsonErrorHandlerTest extends TestCase
{

    public function testShouldBuildJsonResponseFromRouterHttpException(): void
    {
        $handler = new JsonErrorHandler();
        $exception = new NotFoundException();
        $response = $handler->handle($exception);
        $this->assertEquals($exception->getStatusCode(), $response->getStatusCode());
        $this->assertEquals($exception->getMessage(), $response->getReasonPhrase());
        $this->assertEquals(
            $exception->buildJsonResponse(new Response())->getBody()->getContents(),
            $response->getBody()->getContents()
        );
    }

    public function testShouldReturnInternalServerErrorForAllUnknownExceptions(): void
    {
        $handler = new JsonErrorHandler();
        $exception = new Exception('other', 123);
        $response = $handler->handle($exception);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('Internal Server Error', $response->getReasonPhrase());
        $this->assertEquals(
            '{"status_code":500,"reason_phrase":"Internal Server Error","error_code":123}',
            $response->getBody()->getContents()
        );
    }
}
