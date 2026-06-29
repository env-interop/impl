# env-interop/impl

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)
[![PDS Composer Script Names](https://img.shields.io/badge/pds-composer--script--names-blue?style=flat-square)](https://github.com/php-pds/composer-script-names)

Reference implementations of the [Env-Interop][] interfaces for PHP 8.4+.

## Installation

```
composer require env-interop/impl
```

## Usage

Load a base environment file, with an optional local override:

```php
use EnvInterop\Impl\EnvLoader;

new EnvLoader()
    ->loadEnv('.env.ini')
    ->loadEnvIfReadable('.env.local.ini');
```

`loadEnv()` and `replaceEnv()` throw `EnvLoaderException` if a file cannot be
read; the `loadEnvIfReadable()` and `replaceEnvIfReadable()` variants suppress
that.

Read values from the environment:

```php
use EnvInterop\Impl\Env;

$env = new Env();

$pdo = new PDO(
    $env->getEnv('PDO_DSN'),
    $env->getEnv('PDO_USERNAME'),
    $env->getEnv('PDO_PASSWORD'),
);
```

Add or replace environment variables:

```php
use EnvInterop\Impl\EnvSetter;

$setter = new EnvSetter();
$setter->addEnv('FEATURE_FLAG', true);   // only if not already set
$setter->setEnv('DEBUG', false);         // always replaces; null unsets
```

Parse environment contents directly (INI syntax, via `parse_ini_string()`):

```php
use EnvInterop\Impl\EnvParser;

$parsed = new EnvParser()->parseEnv(<<<INI
    APP_NAME = "example"
    APP_DEBUG = true
    INI);
```

`parseEnv()` throws `EnvParserException` on a syntax error, or
`EnvInvalidException` if a parsed value is not null or scalar (such as the array
produced by an INI section).

## Classes

| Interface              | Implementation        |
| ---------------------- | --------------------- |
| _EnvLoaderService_     | `EnvLoader`           |
| _EnvParserService_     | `EnvParser`           |
| _EnvSetterService_     | `EnvSetter`           |
| _EnvGetter_            | `Env`                 |
| _EnvLoaderThrowable_   | `EnvLoaderException`  |
| _EnvParserThrowable_   | `EnvParserException`  |
| _EnvInvalidThrowable_  | `EnvInvalidException` |

All classes are in the `EnvInterop\Impl` namespace.

The three exception classes extend `RuntimeException` and implement
_EnvThrowable_ from the interface package, so catching that single marker
handles any environment error.

See the [Env-Interop][] interface package for the full specification.

[Env-Interop]: https://github.com/env-interop/interface
