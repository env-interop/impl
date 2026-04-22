<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvSetterService;

class EnvSetter implements EnvSetterService
{
    /**
     * @inheritdoc
     */
    public function addEnv(
        int|string $name,
        null|bool|int|float|string $value,
    ) : void
    {
        if (! isset($_ENV[$name]) && $value !== null) {
            $_ENV[$name] = $this->castToString($value);
        }
    }

    /**
     * @inheritdoc
     */
    public function setEnv(
        int|string $name,
        null|bool|int|float|string $value,
    ) : void
    {
        if ($value === null) {
            unset($_ENV[$name]);
            return;
        }

        $_ENV[$name] = $this->castToString($value);
    }

    protected function castToString(bool|int|float|string $value) : string
    {
        if (is_bool($value)) {
            $value = (int) $value;
        }

        return (string) $value;
    }
}
