<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvInvalidThrowable;
use RuntimeException;

class EnvInvalidException extends RuntimeException implements EnvInvalidThrowable
{
}
