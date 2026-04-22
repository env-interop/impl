<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvGetter;

class Env implements EnvGetter
{
    /**
     * @var array<array-key,string>
     */
    protected array $vars;

    /**
     * @param array<array-key,string> $vars
     */
    public function __construct(?array $vars = null)
    {
        /** @var array<array-key,string> $_ENV */
        $this->vars = $vars ?? $_ENV;
    }

    public function getEnv(int|string $name) : ?string
    {
        return $this->vars[$name] ?? null;
    }
}
