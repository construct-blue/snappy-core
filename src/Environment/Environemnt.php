<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Environment;

class Environemnt
{
    private string $directory;
    private float $microtime;

    public function __construct()
    {
        $this->directory = getcwd();
        $this->microtime = microtime(true);
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * @return float
     */
    public function getMicrotime(): float
    {
        return $this->microtime;
    }
}