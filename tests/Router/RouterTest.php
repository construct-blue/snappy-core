<?php

declare(strict_types=1);

namespace Router;

use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\Uri;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SnappyApplication\ErrorHandler\ErrorHandlerInterface;
use SnappyApplication\Router\Handler\ClosureHandler;
use SnappyApplication\Router\RouteGroup;
use SnappyApplication\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testRoutes(): void
    {
        $this->assertRoute('page1', '/page1');
        $this->assertRoute('home', '/');
        $this->assertRoute('example_com_home', 'https://example.com/');
        $this->assertRoute('example_com_page1', 'https://example.com/page1');
        $this->assertRoute('example2_com_page1', 'https://example2.com/page1');
        $this->assertRoute('page2', '/page2');
    }


    public function testShouldUseErrorHandlerForNotFound(): void
    {
        $response = new TextResponse('error');

        $request = $this->createRequestMock('https://www.example.com/invalid/path/that/does/not/exist');
        $router = $this->createRouter($response);

        $this->assertSame($response, $router->handle($request));
    }


    private function assertRoute(string $expectedPage, string $uri, string $method = 'GET'): void
    {
        $router = $this->createRouter();
        $request = $this->createRequestMock($uri, $method);
        self::assertEquals($expectedPage, $router->handle($request)->getBody()->getContents());
    }

    private function createRequestMock(string $uri, string $method = 'GET'): ServerRequestInterface
    {
        $builder = $this->getMockBuilder(ServerRequestInterface::class);
        $builder->onlyMethods(['getUri', 'getMethod', 'withAttribute']);
        $request = $builder->getMockForAbstractClass();
        $request->method('getUri')->willReturn(new Uri($uri));
        $request->method('getMethod')->willReturn($method);
        $request->method('withAttribute')->willReturn($request);
        return $request;
    }

    private function createRouter(ResponseInterface $errorResponse = null): Router
    {
        $errorHandler = $this->getMockBuilder(ErrorHandlerInterface::class)
            ->onlyMethods(['handle'])
            ->getMock();
        $errorHandler->method('handle')->willReturn($errorResponse ?? new TextResponse('error'));
        $router = new Router($errorHandler);

        $router->route('home', '/', new ClosureHandler(fn() => new TextResponse('home')));
        $router->host('example.com', function (RouteGroup $group) {
            $group->route('example_com_home', '/', new ClosureHandler(fn() => new TextResponse('example_com_home')));
            $group->route(
                'example_com_page1',
                '/page1',
                new ClosureHandler(fn() => new TextResponse('example_com_page1'))
            );
            $group->route(
                'example_com_page2',
                '/page2',
                new ClosureHandler(fn() => new TextResponse('example_com_page2'))
            );
        });
        $router->route('page1', '/page1', new ClosureHandler(fn() => new TextResponse('page1')));
        $router->host('example2.com', function (RouteGroup $group) {
            $group->route('example2_com_home', '/', new ClosureHandler(fn() => new TextResponse('example2_com_home')));
            $group->route(
                'example2_com_page1',
                '/page1',
                new ClosureHandler(fn() => new TextResponse('example2_com_page1'))
            );
            $group->route(
                'example2_com_page2',
                '/page2',
                new ClosureHandler(fn() => new TextResponse('example2_com_page2'))
            );
        });
        $router->route('page2', '/page2', new ClosureHandler(fn() => new TextResponse('page2')));
        return $router;
    }
}
