<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\ErrorHandler;

use Laminas\Diactoros\Response\TextResponse;
use League\Route\Http\Exception\HttpExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class TextErrorHandler implements ErrorHandlerInterface
{
    public function handle(Throwable $throwable, ?ServerRequestInterface $request = null): ResponseInterface
    {
        if ($throwable instanceof HttpExceptionInterface) {
            $response = new TextResponse($throwable->getMessage(), $throwable->getStatusCode());
            foreach ($throwable->getHeaders() as $key => $value) {
                $response = $response->withAddedHeader($key, $value);
            }
            return $response;
        }
        return new TextResponse('Internal Server Error', 500);
    }
}