<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Assets;

use Blue\Snappy\Renderer\Renderable;
use Blue\Snappy\Renderer\Renderer;

class Entrypoint implements Renderable
{
    public function __construct(private Assets $assets)
    {
    }

    public function render(Renderer $renderer, $data = null): iterable
    {
        yield from $this->assets->getCss();
        yield from $this->assets->getJs();
    }
}