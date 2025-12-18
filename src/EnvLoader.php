<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvLoaderService;
use EnvInterop\Interface\EnvParserService;
use EnvInterop\Interface\EnvSetterService;

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
    public function loadEnv(
        string $filename,
        bool $override = false,
    ) : static
    {
        return file_exists($filename) && is_file($filename)
            ? $this->loadEnvFile($filename, $override)
            : throw new EnvException("Env file '{$filename}' does not exist.");
    }

    /**
     * @inheritdoc
     */
    public function loadEnvIfExists(
        string $filename,
        bool $override = false,
    ) : static
    {
        return file_exists($filename) && is_file($filename)
            ? $this->loadEnvFile($filename, $override)
            : $this;
    }

    protected function loadEnvFile(string $filename, bool $override) : static
    {
        if (! is_readable($filename)) {
            throw new EnvException("Env file {$filename} is not readable.");
        }

        $contents = file_get_contents($filename);

        if (! is_string($contents)) {
            throw new EnvException("Could not read from env file {$filename}.");
        }

        $parsed = $this->envParser->parseEnv($contents);

        foreach ($parsed as $name => $value) {
            $this->envSetter->setEnv($name, $value, $override);
        };

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function assertEnv(array $names = []) : void
    {
        foreach ($names as $i => $name) {
            if (isset($_ENV[$name])) {
                unset($names[$i]);
            }
        }

        if (! $names) {
            return;
        }

        $message = "The following environment variables are not set: "
            . "'"
            . implode("', '", $names)
            . "'";

        throw new EnvException($message);
    }
}
