<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$app = new SnappyApplication\Application(
    new SnappyApplication\Emitter\SapiResponseEmitter(),
    new SnappyApplication\Request\SapiServerRequestFactory(),
    new SnappyApplication\ErrorHandler\HtmlErrorHandler(new SnappyRenderer\Renderer())
);

$app->run();