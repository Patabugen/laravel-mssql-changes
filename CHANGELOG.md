# Changelog

All notable changes to `laravel-mssql-changes` will be documented in this file.

## Unreleasd Changed

 - Added: Tests for Enable and Disable tables
 - Fixed: Output of ListTables to count Enabled and Disabled tests
 - Fixed: Put basic sanitising of table names back into EnableTable

## 1.2
 
 - Corrected typo in docs
 - Add --all command to enable-table-change-tracking command

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
