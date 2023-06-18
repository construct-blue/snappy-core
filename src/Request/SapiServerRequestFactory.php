<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Request;

use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

class SapiServerRequestFactory implements ServerRequestFactoryInterface
{
    public function create(): ServerRequestInterface
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    }
}