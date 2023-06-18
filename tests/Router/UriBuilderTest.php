<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Core\Router;

use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\Uri;
use League\Route\Router;
use PHPUnit\Framework\TestCase;
use Blue\Snappy\Core\Router\UriBuilder;

class UriBuilderTest extends TestCase
{
    public function testShouldBuildUriForNamedRoute(): void
    {
        $router = new Router();
        $router->map('GET', '/page', fn() => new TextResponse('test'))
            ->setName('p1');

        $uri = new Uri('https://example.com/');

        $uriBuilder = new UriBuilder($router, $uri);

        self::assertEquals(
            (string)$uri->withPath('/page'),
            (string)$uriBuilder->withRouteName('p1')->build()
        );
    }


    public function testShouldReplacePlaceholdersInUriForNamedRoute(): void
    {
        $router = new Router();
        $router->map('GET', '/page/{code}', fn() => new TextResponse('test'))
            ->setName('p2');

        $uri = new Uri('https://example.com/');

        $uriBuilder = new UriBuilder($router, $uri);

        self::assertEquals(
            (string)$uri->withPath('/page/foo'),
            (string)$uriBuilder->withRouteName('p2')->build(['code' => 'foo'])
        );
    }
}
