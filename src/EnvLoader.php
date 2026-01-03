<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvLoaderService;
use EnvInterop\Interface\EnvLoaderThrowable;
use EnvInterop\Interface\EnvParserService;
use EnvInterop\Interface\EnvSetterService;
use EnvInterop\Interface\EnvTypeAliases;

/**
 * @phpstan-import-type env_parsed_array from EnvTypeAliases
 */
class EnvLoader implements EnvLoaderService
{
    public function __construct(
        protected EnvParserService $envParser = new EnvParser(),
        protected EnvSetterService $envSetter = new EnvSetter(),
    ) {
    }

    /**
     * @inheritdoc
     */
    public function loadEnv(string $filename) : static
    {
        $parsed = $this->parseEnvFile($filename);

        foreach ($parsed as $name => $value) {
            $this->envSetter->addEnv($name, $value);
        }

        return $this;
    }

    public function loadEnvIfReadable(string $filename) : static
    {
        try {
            return $this->loadEnv($filename);
        } catch (EnvLoaderThrowable) {
            return $this;
        }
    }

    /**
     * @inheritdoc
     */
    public function replaceEnv(string $filename) : static
    {
        $parsed = $this->parseEnvFile($filename);

        foreach ($parsed as $name => $value) {
            $this->envSetter->setEnv($name, $value);
        }

        return $this;
    }

    public function replaceEnvIfReadable(string $filename) : static
    {
        try {
            return $this->replaceEnv($filename);
        } catch (EnvLoaderThrowable) {
            return $this;
        }
    }

    /**
     * @return env_parsed_array
     */
    protected function parseEnvFile(string $filename) : array
    {
        $isExistingReadableFile = file_exists($filename)
            && is_readable($filename)
            && is_file($filename);

        if (! $isExistingReadableFile) {
            throw new EnvLoaderException("Could not read env file '{$filename}'.");
        }

        $errorLevel = error_reporting(0);
        $contents = file_get_contents($filename);
        error_reporting($errorLevel);

        if (! is_string($contents)) {
            $error = error_get_last();
            $message = trim($error['message'] ?? '');
            throw new EnvLoaderException("Could not read env file '{$filename}': {$message}");
        }

        return $this->envParser->parseEnv($contents);
    }

    /**
     * @inheritdoc
     */
    public function assertEnv(array $names = []) : static
    {
        foreach ($names as $i => $name) {
            if (isset($_ENV[$name])) {
                unset($names[$i]);
            }
        }

        if ($names) {
            $message = "The following environment variables are not set: "
                . "'"
                . implode("', '", $names)
                . "'";

            throw new EnvInvalidException($message);
        }

        return $this;
    }
}
