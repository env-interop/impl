<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvParserService;
use EnvInterop\Interface\EnvTypeAliases;

/**
 * @phpstan-import-type env_parsed_array from EnvTypeAliases
 */
class EnvParser implements EnvParserService
{
    /**
     * @inheritdoc
     */
    public function parseEnv(string $contents) : array
    {
        $errorLevel = error_reporting(0);
        $parsed = parse_ini_string($contents, scanner_mode: INI_SCANNER_TYPED);
        error_reporting($errorLevel);

        if ($parsed === false) {
            $error = error_get_last();
            $message = trim($error['message'] ?? '');
            throw new EnvException("Could not parse env string: {$message}");
        }

        foreach ($parsed as $name => $value) {
            if (! is_null($value) && ! is_scalar($value)) {
                $message = "Expected env var '"
                    . $name
                    . "' to be null or scalar, actually "
                    . gettype($value)
                    . ".";

                throw new EnvException($message);
            }
        }

        /** @var env_parsed_array $parsed */
        return $parsed;
    }
}
