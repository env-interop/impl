<?php
declare(strict_types=1);

namespace EnvInterop\Impl;

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
        $this->expectException(EnvParserException::class);

        $this->expectExceptionMessage(
            "Could not parse env string: syntax error, unexpected '^' in Unknown on line 1",
        );

        $this->envParser->parseEnv($envString);
    }

    public function testParseEnv_invalidValues() : void
    {
        $envString = <<<'ENVSTRING'
            foo[]=bar
            foo[]=baz
            foo[]=dib
        ENVSTRING;

        $this->expectException(EnvInvalidException::class);

        $this->expectExceptionMessage(
            "Expected env var 'foo' to be null or scalar, actually array.",
        );

        $this->envParser->parseEnv($envString);
    }

    public function testParseEnv_numericName() : void
    {
        $parsed = $this->envParser->parseEnv('123=foo');
        $this->assertSame([123 => 'foo'], $parsed);
    }
}
