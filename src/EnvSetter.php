<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvSetterService;

class EnvSetter implements EnvSetterService
{
    /**
     * @inheritdoc
     */
    public function setEnv(
        string $name,
        null|bool|int|float|string $value,
        bool $override = false
    ) : void
    {
        if (isset($_ENV[$name]) && ! $override) {
            return;
        }

        if ($value === null) {
            unset($_ENV[$name]);
            return;
        }

        if (is_bool($value)) {
            $_ENV[$name] = (string) (int) $value;
            return;
        }

        $_ENV[$name] = (string) $value;
    }
}
