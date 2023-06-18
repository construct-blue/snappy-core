<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Request;

interface ServerRequestFactoryInterface
{
    public function create();
}