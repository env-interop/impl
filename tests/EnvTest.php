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

    public function testGetEnv_numericName() : void
    {
        $env = new Env([123 => 'bar']);
        $this->assertSame('bar', $env->getEnv(123));
        $this->assertSame('bar', $env->getEnv('123'));
    }
}
