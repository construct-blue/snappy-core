<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;

return fn(ServerRequestInterface $request) => <<<HTML
<table>
<tr><td>URI</td><td>{$request->getUri()}</td></tr>
<tr><td>Method</td><td>{$request->getMethod()}</td></tr>
<tr><td>Protocol</td><td>{$request->getProtocolVersion()}</td></tr>
</table>
HTML;
