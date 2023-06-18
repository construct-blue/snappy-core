<?php

declare(strict_types=1);

namespace Snappy\Core\Request;

interface ServerRequestFactoryInterface
{
    public function create();
}