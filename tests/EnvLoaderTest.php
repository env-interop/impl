<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use PHPUnit\Framework\Attributes\BackupGlobals;

#[BackupGlobals(true)]
class EnvLoaderTest extends \PHPUnit\Framework\TestCase
{
    protected EnvLoader $envLoader;

    protected string $filename;

    protected function setUp() : void
    {
        $_ENV = ['FOO' => 'original'];
        $this->envLoader = new EnvLoader();
        $this->filename = __DIR__ . DIRECTORY_SEPARATOR . '.env.ini';
    }

    public function testLoadEnv() : void
    {
        $this->envLoader->loadEnv($this->filename);

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

    public function testLoadEnv_notReadable() : void
    {
        $this->expectException(EnvLoaderException::class);
        $this->expectExceptionMessage("Could not read env file 'no-such-file'.");
        $this->envLoader->loadEnv('no-such-file');
    }

    public function testLoadEnvIfReadable() : void
    {
        $this->envLoader->loadEnvIfReadable($this->filename);

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

    public function testLoadEnvIfReadable_notReadable() : void
    {
        $this->envLoader->loadEnvIfReadable('no-such-file');
        $expect = ['FOO' => 'original'];
        $this->assertSame($expect, $_ENV);
    }

    public function testReplaceEnv() : void
    {
        $this->envLoader->replaceEnv($this->filename);

        $expect = [
            'FOO' => 'override',
            'INT_THREE' => '3',
            'BOOL_TRUE' => '1',
            'BOOL_FALSE' => '0',
            'FLOAT_PI' => '3.1415',
            'STRING_EMPTY' => '',
        ];

        $this->assertSame($_ENV, $expect);
    }

    public function testReplaceEnv_nullRemovesExisting() : void
    {
        $_ENV['NULL_UNSET'] = 'preexisting';
        $this->envLoader->replaceEnv($this->filename);
        $this->assertArrayNotHasKey('NULL_UNSET', $_ENV);
    }

    public function testReplaceEnv_notReadable() : void
    {
        $this->expectException(EnvLoaderException::class);
        $this->expectExceptionMessage("Could not read env file 'no-such-file'.");
        $this->envLoader->replaceEnv('no-such-file');
    }

    public function testReplaceEnvIfReadable() : void
    {
        $this->envLoader->replaceEnvIfReadable($this->filename);

        $expect = [
            'FOO' => 'override',
            'INT_THREE' => '3',
            'BOOL_TRUE' => '1',
            'BOOL_FALSE' => '0',
            'FLOAT_PI' => '3.1415',
            'STRING_EMPTY' => '',
        ];

        $this->assertSame($_ENV, $expect);
    }

    public function testReplaceEnvIfReadable_notReadable() : void
    {
        $this->envLoader->replaceEnvIfReadable('no-such-file');
        $expect = ['FOO' => 'original'];
        $this->assertSame($expect, $_ENV);
    }
}
