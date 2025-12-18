<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

class EnvTest extends \PHPUnit\Framework\TestCase
{
    public function testGetEnv() : void
    {
        $env = new Env(['FOO' => 'bar']);
        $this->assertSame('bar', $env->getEnv('FOO'));
        $this->assertNull($env->getEnv('BAZ'));
    }
}
