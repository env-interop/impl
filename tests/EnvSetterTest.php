<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use PHPUnit\Framework\Attributes\BackupGlobals;

#[BackupGlobals(true)]
class EnvSetterTest extends \PHPUnit\Framework\TestCase
{
    protected EnvSetter $envSetter;

    protected string $filename;

    protected function setUp() : void
    {
        $_ENV = ['FOO' => 'original'];
        $this->envSetter = new EnvSetter();
    }

    public function testSetEnv() : void
    {
        $this->envSetter->setEnv('FOO', 'override');
        $this->envSetter->setEnv('INT_THREE', 3);
        $this->envSetter->setEnv('BOOL_TRUE', true);
        $this->envSetter->setEnv('BOOL_FALSE', false);
        $this->envSetter->setEnv('FLOAT_PI', 3.1415);
        $this->envSetter->setEnv('STRING_EMPTY', '');
        $this->envSetter->setEnv('NULL_UNSET', null);

        $expect = [
            'FOO' => 'original',
            'INT_THREE' => '3',
            'BOOL_TRUE' => '1',
            'BOOL_FALSE' => '0',
            'FLOAT_PI' => '3.1415',
            'STRING_EMPTY' => '',
        ];

        $this->assertSame($_ENV, $expect);
    }

    public function testSetEnv_override() : void
    {
        $this->envSetter->setEnv(
            name: 'FOO',
            value: 'override',
            override: true,
        );

        $expect = [
            'FOO' => 'override',
        ];

        $this->assertSame($_ENV, $expect);
    }
}
