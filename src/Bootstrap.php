<?php

declare(strict_types=1);

namespace SnappyApplication;

interface Bootstrap
{
    public function boot(Kernel $kernel): void;
}