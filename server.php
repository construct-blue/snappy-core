<?php

declare(strict_types=1);

require 'vendor/autoload.php';

$app = new Snappy\Core\Http(
    new Snappy\Core\Emitter\SapiResponseEmitter(),
    new Snappy\Core\Request\SapiServerRequestFactory(),
    new Snappy\Core\ErrorHandler\HtmlErrorHandler(new SnappyRenderer\Renderer())
);

