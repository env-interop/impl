# Env-Interop Reference Implementation

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)
[![PDS Composer Script Names](https://img.shields.io/badge/pds-composer--script--names-blue?style=flat-square)](https://github.com/php-pds/composer-script-names)

The reference _EnvLoaderService_ implementation uses `parse_ini_string()` for
environment file parsing, and loads only into `$_ENV`.

```php
use EnvInterop\Impl\EnvLoader;

// loads a base required file and an optional local file,
// then checks that required variables have been loaded.
new EnvLoader()
    ->loadEnv('.env.ini')
    ->loadEnvIfExists('.env.local.ini')
    ->assertEnv([
        'PDO_DSN',
        'PDO_USERNAME',
        'PDO_PASSWORD',
    ]);
```

The reference implementation for _EnvGetter_ reads from a copy of `$_ENV`.

```php
use EnvInterop\Impl\Env;
use PDO;

$env = new Env();

$pdo = PDO::connect(
    $env->getEnv('PDO_DSN'),
    $env->getEnv('PDO_USERNAME'),
    $env->getEnv('PDO_PASSWORD'),
);
```
