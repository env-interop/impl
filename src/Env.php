<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use EnvInterop\Interface\EnvGetter;

class Env implements EnvGetter
{
    /**
     * @var array<string,string>
     */
    protected array $vars;

    /**
     * @param array<string,string> $vars
     */
    public function __construct(?array $vars = null)
    {
        /** @var array<string,string> $_ENV */
        $this->vars = $vars ?? $_ENV;
    }

    public function getEnv(string $name) : ?string
    {
        return $this->vars[$name] ?? null;
    }
}
