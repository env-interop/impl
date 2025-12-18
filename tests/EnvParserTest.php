<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

use PHPUnit\Framework\Attributes\BackupGlobals;

class EnvParserTest extends \PHPUnit\Framework\TestCase
{
    protected EnvParser $envParser;

    public function setUp() : void
    {
        $this->envParser = new EnvParser();
    }

    public function testParseEnv() : void
    {
        $contents = (string) file_get_contents(
            __DIR__ . DIRECTORY_SEPARATOR . '.env.ini',
        );

        $actual = $this->envParser->parseEnv($contents);

        $expect = [
            'FOO' => 'override',
            'INT_THREE' => 3,
            'BOOL_TRUE' => true,
            'BOOL_FALSE' => false,
            'FLOAT_PI' => 3.1415,
            'STRING_EMPTY' => '',
            'NULL_UNSET' => null,
        ];

        $this->assertSame($expect, $actual);
    }

    public function testParseEnv_parserFails() : void
    {
        $envString = '^badstring$';
        $this->expectException(EnvException::class);
        $this->expectExceptionMessage("Could not parse env string: syntax error, unexpected '^' in Unknown on line 1");
        $this->envParser->parseEnv($envString);
    }

    public function testParseEnv_invalidValues() : void
    {
        $envString = <<<'ENVSTRING'
            foo[]=bar
            foo[]=baz
            foo[]=dib
        ENVSTRING;

        $this->expectException(EnvException::class);
        $this->expectExceptionMessage("Expected env var 'foo' to be null or scalar, actually array.");
        $this->envParser->parseEnv($envString);
    }
}
