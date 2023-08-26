<?php

declare(strict_types=1);

namespace BlueTest\Snappy\Core\Assets;

use Blue\Snappy\Core\Assets\AssetsLoader;
use Blue\Snappy\Core\Assets\Exception\InvalidManifestException;
use Blue\Snappy\Renderer\Renderer;
use PHPUnit\Framework\TestCase;

class AssetsLoaderTest extends TestCase
{
    public function testShouldThrowExceptionWhenManifestFileIsMissing()
    {
        self::expectException(InvalidManifestException::class);
        self::expectExceptionCode(InvalidManifestException::CODE_FILE_MISSING);
        new AssetsLoader('');
    }

    public function testShouldThrowExceptionWhenManifestFileIsUnreadable()
    {
        self::expectException(InvalidManifestException::class);
        self::expectExceptionCode(InvalidManifestException::CODE_FILE_UNREADABLE);
        new AssetsLoader(__DIR__ . '/invalid.json');
    }

    public function testShouldThrowExceptionWhenManifestFileIsEmpty()
    {
        self::expectException(InvalidManifestException::class);
        self::expectExceptionCode(InvalidManifestException::CODE_FILE_INVALID);
        new AssetsLoader(__DIR__ . '/empty.json');
    }

    public function testShouldThrowExceptionWhenManifestFileHasNoEntrypointsKey()
    {
        self::expectException(InvalidManifestException::class);
        self::expectExceptionCode(InvalidManifestException::CODE_FILE_INVALID);
        new AssetsLoader(__DIR__ . '/no-entrypoints.json');
    }

    public function testShouldRenderScriptHtml()
    {
        $loader = new AssetsLoader(__DIR__ . '/valid.json');
        self::assertEquals(
            file_get_contents(__DIR__ . '/expected'),
            (new Renderer())->render($loader)
        );
    }
}
