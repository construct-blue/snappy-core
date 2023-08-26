<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Request;

use Psr\Http\Message\ServerRequestInterface;

interface ServerRequestFactoryInterface
{
    public function create(): ServerRequestInterface;
}