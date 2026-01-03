<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvParserThrowable;
use RuntimeException;

class EnvParserException extends RuntimeException implements EnvParserThrowable
{
}
