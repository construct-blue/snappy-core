<?php

declare(strict_types=1);

namespace SnappyApplication;

use SnappyApplication\Router\RouterConfigurator;

class ConfigBootstrap implements Bootstrap
{
    private array $config;

    private RouterConfigurator $routerConfigurator;

    public function __construct(array $config, RouterConfigurator $routerConfigurator)
    {
        $this->config = $config;
        $this->routerConfigurator = $routerConfigurator;
    }

    public function boot(Kernel $kernel): void
    {
        $this->routerConfigurator->configure($kernel->getRouter(), $this->config['router'] ?? []);
    }
}