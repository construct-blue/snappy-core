<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Assets;

class Assets
{
    /**
     * @param Script[] $js
     * @param Stylesheet[] $css
     */
    public function __construct(private array $js, private array $css)
    {
    }

    /**
     * @return Script[]
     */
    public function getJs(): array
    {
        return $this->js;
    }

    /**
     * @return Stylesheet[]
     */
    public function getCss(): array
    {
        return $this->css;
    }
}