# Changelog

All notable changes to `laravel-mssql-changes` will be documented in this file.

## Unreleasd Changed

<<<<<<< HEAD
 - Improve the console output of `mssql:show-changes` by word-wrapping the Columns Changed column and adding separators between rows.
 - Added config option: MSSQL_COLUMNS_CHANGED_MAX_WIDTH to override the default Columns Changes width
=======
 - Fixed: support composite primary keys in ListTableChanges
>>>>>>> 0ec961f (Fixed: support composite primary keys in ListTableChanges)

## 1.2.0 - 2022-11-21

This update contains a breaking change, but since the library is brand new and nobody else is using it I'm not bumping the Major number.

 - Breaking: Enable and Disable table `handle` commands require a Table object, not a string
 - Corrected typo in docs
 - Add --all command to enable-table-change-tracking command
 - Added: Tests for Enable and Disable tables
 - Fixed: Output of ListTables to count Enabled and Disabled tests
 - Fixed: Put basic sanitising of table names back into EnableTable

## 1.1.0 - 2022-10-17

 - Sort ShowChanges output by Change Version (without grouping by Table)
 - Create a test database for use with tests
 - Added DisableTableChangeTracking action and command

## 1.0.0 - 2022-10-10

Initial Release

 - Enable Tracking on Database and Tables
 - List databases with tracking enabled
 - List tables with tracking enabled
 - Get the latest Change version for a database
 - List tracked changes for a particular table
 - List all changes for all tracked tables
