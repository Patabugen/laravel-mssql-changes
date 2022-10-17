# A package to manage Change Tracking and view changes in Microsoft's SQL Server (2008) from Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/patabugen/laravel-mssql-changes.svg?style=flat-square)](https://packagist.org/packages/patabugen/laravel-mssql-changes)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/patabugen/laravel-mssql-changes/run-tests?label=tests)](https://github.com/patabugen/laravel-mssql-changes/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/patabugen/laravel-mssql-changes/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/patabugen/laravel-mssql-changes/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/patabugen/laravel-mssql-changes.svg?style=flat-square)](https://packagist.org/packages/patabugen/laravel-mssql-changes)

Manage tracked changes in Microsoft SQLServer, letting you list changes from Artisan and inside your Laravel application.

This package was written to read tracked changes from MSSQL 2008 and has not been tested on other versions.

> Note: This library is for [Change Tracking](https://learn.microsoft.com/en-us/sql/relational-databases/track-changes/manage-change-tracking-sql-server?view=sql-server-ver16) which is distinct from [Change Data Capture (CDC)](https://learn.microsoft.com/en-us/sql/relational-databases/track-changes/about-change-data-capture-sql-server?view=sql-server-ver16).

## Installation

You can install the package via composer:

```bash
composer require patabugen/laravel-mssql-changes
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="mssql-changes-config"
```

## Usage - PHP
Features are provided by Actions, which are used by Artisan commands to give us CLI access.

See the `asCommand` method on each action for real-life examples of calling the library from PHP.

### Get all changes for a table
```php
    use Patabugen\MssqlChanges\Actions\ListTableChanges;
    use Patabugen\SqlChanges\Table;
    use Patabugen\SqlChanges\Change;
    $table = Table::create('Contacts');
    /** @var Illuminate\Support\Collection $changes */
    $changes = ListTableChanges::handle($table);
    $changes->each(function(Change $change) {
        var_dump($change->toArray();
    });
```

### Get all changes for a table filtered by version
```php
    use Patabugen\MssqlChanges\Actions\ListTableChanges;
    use Patabugen\SqlChanges\Table;
    use Patabugen\SqlChanges\Change;
    $table = Table::create('Contacts');
    /** @var Illuminate\Support\Collection $changes */
    $changes = ListTableChanges::make()
        ->fromVersion(100)
        ->toVersion(200)
        ->handle($table);
    $changes->each(function(Change $change) {
        var_dump($change->toArray();
    });
```

## Usage - Artisan

__Note: This package is in it's early stages, these commands may not work yet.__

The default database from your config will be used, or set environment variable `MSSQL_CHANGES_CONNECTION` to the name of the connection to use.

### Enable change tracking for the database
`artisan mssql:enable-change-tracking`

### Enable change tracking for a table
`artisan mssql:enable-table-change-tracking {TableName}`

### Disable change tracking for a table
`artisan mssql:disable-table-change-tracking {TableName}`

### Lists all changes in all tables.
`artisan mssql:show-changes`

### Filter Changes by table
`artisan mssql:show-table-changes {tableName}`

### Filter changes by Version
Use `--from` and/or `--to` to only show changes before or after a given change (inclusive).

`artisan mssql:show-changes --from=200 --to=209`
`artisan mssql:show-table-changes --from=200 --to=209`

```shell
$ php artisan mssql:list-table-changes Addresses
+-----------+-------------+---------------------+----------------+
| Table     | Primary Key | Columns Changed     | Change Version |
+-----------+-------------+---------------------+----------------+
| Addresses | 91750       | Address1, upsize_ts | 5              |
+-----------+-------------+---------------------+----------------+
```

## Todo
I'd like to add these commands or features:

 - Creating a test database for the tests
 - `artisan mssql:disable-change-tracking`
 - `artisan mssql:list-status` - Show the status of databases/tables

## Testing
You'll need a running instance of SQL server with a database already created, see `phpunit.xml.dist` for default values. Copy it to `phpunit.xml` to customise the tests.

If you have not already, you may need to take [extra steps](https://docs.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac?view=sql-server-ver16) to allow your PHP to connect to MSSQL. 

> Note: At the time of writing the tests are not fully functional because I've started getting them to create a test database, but not finished it. You may be able to remove the migrations/setup from TestCase and manually create a table called "Contacts" with tracking enabled.

> The tests also have some hard-coded numbers which mean they'll break often. That can be solved by finishing the above so RefreshDatabase works, or by using GetVersion to filter the results within each test before making assertions.

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

This package has been created to solve a specific issue in a one-off project so is not likely to receive long term updates.

I've released it as a package in case it might help somebody else one day.

Pull requests are very welcome, especially if they include tests or round out existing/core features. Feel free to submit an issue to discuss a change you'd like.

## Credits

- [Sami Walbury](https://github.com/Patabugen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
