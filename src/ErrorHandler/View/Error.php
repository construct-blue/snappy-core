<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use SnappyRenderer\Helper\Arguments;

return fn(string $message, int $code, ?ServerRequestInterface $request, SnappyRenderer\Renderer $r) => <<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error: $code</title>
</head>
<body>
  <h1>$message</h1>
  {$r->conditional(include 'RequestDetails.php', fn() => isset($request), new Arguments(['request' => $request]))}
</body>
</html>
HTML;
