<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use PHPUnit\Framework\Attributes\BackupGlobals;

#[BackupGlobals(true)]
class EnvTest extends \PHPUnit\Framework\TestCase
{
    public function testGetEnv() : void
    {
        $env = new Env(['FOO' => 'bar']);
        $this->assertSame('bar', $env->getEnv('FOO'));
        $this->assertNull($env->getEnv('BAZ'));
    }

    public function testGetEnv_numericName() : void
    {
        $env = new Env([123 => 'bar']);
        $this->assertSame('bar', $env->getEnv(123));
        $this->assertSame('bar', $env->getEnv('123'));
    }

    public function testGetEnv_defaultsToEnvGlobal() : void
    {
        $_ENV = ['FOO' => 'from-global', 'BAZ' => 'also-global'];
        $env = new Env();
        $this->assertSame('from-global', $env->getEnv('FOO'));
        $this->assertSame('also-global', $env->getEnv('BAZ'));
        $this->assertNull($env->getEnv('MISSING'));
    }
}
