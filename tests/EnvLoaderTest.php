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

    public function testLoadEnv_override() : void
    {
        $this->envLoader->loadEnv($this->filename, override: true);

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

    public function testLoadEnv_doesNotExist() : void
    {
        $this->expectException(EnvException::class);
        $this->expectExceptionMessage("Env file 'no-such-file' does not exist.");
        $this->envLoader->loadEnv('no-such-file');
    }

    public function testLoadEnvIfExists() : void
    {
        $this->envLoader->loadEnvIfExists($this->filename);

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

    public function testLoadEnvIfExists_doesNotExist() : void
    {
        $this->envLoader->loadEnvIfExists('no-such-file');
        $this->assertSame(['FOO' => 'original'], $_ENV);
    }

    public function testAssertEnv() : void
    {
        $_ENV['FOO'] = 'bar';
        $this->envLoader->assertEnv(['FOO']);

        $this->expectException(EnvException::class);
        $this->expectExceptionMessage("The following environment variables are not set: 'BAR', 'BAZ'");
        $this->envLoader->assertEnv(['FOO', 'BAR', 'BAZ']);
    }
}
