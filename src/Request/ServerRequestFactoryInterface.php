<?php

declare(strict_types=1);

namespace SnappyApplication\Request;

interface ServerRequestFactoryInterface
{
    public function create();
}