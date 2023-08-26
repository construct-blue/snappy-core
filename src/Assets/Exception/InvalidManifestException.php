<?php

declare(strict_types=1);

namespace Blue\Snappy\Core\Assets\Exception;

use Exception;

class InvalidManifestException extends Exception
{
    public const CODE_FILE_MISSING = 1;
    public const CODE_FILE_UNREADABLE = 2;
    public const CODE_FILE_INVALID = 3;
}