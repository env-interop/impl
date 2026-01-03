<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvLoaderThrowable;
use RuntimeException;

class EnvLoaderException extends RuntimeException implements EnvLoaderThrowable
{
}
