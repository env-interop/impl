<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvThrowable;
use RuntimeException;

class EnvException extends RuntimeException implements EnvThrowable
{
}
