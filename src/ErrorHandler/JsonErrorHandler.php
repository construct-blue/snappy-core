<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\ErrorHandler;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\JsonResponse;
use League\Route\Http\Exception\HttpExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class JsonErrorHandler implements ErrorHandlerInterface
{
    public function handle(Throwable $throwable, ?ServerRequestInterface $request = null): ResponseInterface
    {
        if ($throwable instanceof HttpExceptionInterface) {
            return $throwable->buildJsonResponse(new Response());
        }
        return new JsonResponse([
            'status_code' => 500,
            'reason_phrase' => 'Internal Server Error',
            'error_code' => $throwable->getCode(),
        ], 500);
    }
}