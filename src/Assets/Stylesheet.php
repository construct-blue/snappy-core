<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Assets;

use Blue\Snappy\Renderer\Renderable;
use Blue\Snappy\Renderer\Renderer;

class Stylesheet implements Renderable
{
    public function __construct(private string $src, private string $integrity)
    {
    }

    public function render(Renderer $renderer, $data = null): iterable
    {
        yield <<<HTML
<link rel="stylesheet" href="$this->src" integrity="$this->integrity">
HTML;
    }
}