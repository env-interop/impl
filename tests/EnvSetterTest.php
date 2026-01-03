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

    public function testAddEnv() : void
    {
        $this->envSetter->addEnv('FOO', 'override');
        $this->envSetter->addEnv('INT_THREE', 3);
        $this->envSetter->addEnv('BOOL_TRUE', true);
        $this->envSetter->addEnv('BOOL_FALSE', false);
        $this->envSetter->addEnv('FLOAT_PI', 3.1415);
        $this->envSetter->addEnv('STRING_EMPTY', '');
        $this->envSetter->addEnv('NULL_UNSET', null);

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

    public function testSetEnv() : void
    {
        $this->envSetter->setEnv(
            name: 'FOO',
            value: 'override',
        );

        $expect = [
            'FOO' => 'override',
        ];

        $this->assertSame($_ENV, $expect);
    }
}
