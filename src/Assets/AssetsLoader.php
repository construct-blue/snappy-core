<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Assets;

use Blue\Snappy\Core\Assets\Exception\InvalidManifestException;
use Blue\Snappy\Renderer\Renderable;
use Blue\Snappy\Renderer\Renderer;
use stdClass;

class AssetsLoader implements Renderable
{
    /**
     * @var Entrypoint[]
     */
    private array $entrypoints;

    /**
     * @param string $manifest
     * @throws InvalidManifestException
     */
    public function __construct(string $manifest)
    {
        if (!$manifest) {
            throw new InvalidManifestException(
                'Manifest file does not exist.',
                InvalidManifestException::CODE_FILE_MISSING
            );
        }
        $manifestJson = file_get_contents($manifest);
        if (false === $manifestJson) {
            throw new InvalidManifestException(
                'Manifest file could not be read.',
                InvalidManifestException::CODE_FILE_UNREADABLE
            );
        }
        $decoded = json_decode($manifestJson);
        unset($manifestJson);
        if (!$decoded instanceof stdClass) {
            throw new InvalidManifestException(
                'Manifest file could not be decoded.',
                InvalidManifestException::CODE_FILE_INVALID
            );
        }

        if (!isset($decoded->entrypoints)) {
            throw new InvalidManifestException(
                'Manifest file has invalid schema.',
                InvalidManifestException::CODE_FILE_INVALID
            );
        }

        foreach ($decoded->entrypoints as $entrypointName => $data) {
            $this->entrypoints[$entrypointName] = new Entrypoint(
                new Assets(
                    js: array_map(fn(stdClass $js) => new Script($js->src, $js->integrity), $data->assets?->js ?? []),
                    css: array_map(fn(stdClass $css) => new Stylesheet($css->src, $css->integrity), $data->assets?->css ?? [])
                )
            );
        }
    }

    public function getEntrypoint(string $name = null): Entrypoint
    {
        if (null === $name) {
            return reset($this->entrypoints);
        }
        return $this->entrypoints[$name];
    }

    public function render(Renderer $renderer, $data = null): iterable
    {
        yield $this->getEntrypoint();
    }
}