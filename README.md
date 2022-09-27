# A package to manage Change Tracking and view changes in Microsoft's SQL Server (2008) from Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/patabugen/laravel-mssql-changes.svg?style=flat-square)](https://packagist.org/packages/patabugen/laravel-mssql-changes)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/patabugen/laravel-mssql-changes/run-tests?label=tests)](https://github.com/patabugen/laravel-mssql-changes/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/patabugen/laravel-mssql-changes/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/patabugen/laravel-mssql-changes/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/patabugen/laravel-mssql-changes.svg?style=flat-square)](https://packagist.org/packages/patabugen/laravel-mssql-changes)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require patabugen/laravel-mssql-changes
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="mssql-changes-config"
```

## Usage

__Note: This package is in it's early stages, these commands may not work yet.__

### Lists all changes in all tables.
`artisan mssql:show-changes`

### Forget all changes
`artisan mssql:forget-changes`

### Filter Changes by table
`artisan mssql:show-changes --table=tableName --table=anotherTable`

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sami Walbury](https://github.com/Patabugen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
