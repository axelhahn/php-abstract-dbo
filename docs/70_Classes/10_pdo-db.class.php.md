## Introduction

The file `pdo-db.class.php` sets a database connection.
It has basic methods for executing queries and logging that will be used by all pdo-db-objects. So you have access to all debug logs or executed queries for all your objects in your application.

## Init

### Constructor

In the contructor you can initialize the database connection, the behaviur on errors and can enable the debug mode.

All keys are optional and can be enabled in seperate methods.

```php
$oDB=new axelhahn\pdo_db([
    'showdebug'=>{bool},
    'showerrors'=>{bool},
    'cfgfile'=>{string} <FILE>,
    'db'=>{array} <DB-CONFIG>,
]);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {array}     | see keys below

To define a database connection you can use one of the the keys

* cfgfile
* db

Without given db or cfgfile the pdo_db class tries to load `pdo-db.config.php` (if this exists).


Keys for the pdo_db constructor

| Key                          | Type        | Description
|---                           |:---:        |---
| cfgfile                      | {string}    | set file with path to a config to create a PDO connection; default: ``pdo-db.config.php`` in the ./vendor/php-abstract-dbo/src/ directrory
| showdebug                    | {bool}      | enable debugging
| showerrors                   | {bool}      | enable to show any error

### Database connection config

The config file and the key "db" can contain these keys

| Key                          | Type        | Description
|---                           |:---:        |---
| dsn                          | {string}    | a dsn; 👉 see also: https://www.php.net/manual/de/pdo.construct.php
| user                         | {string}    | optional: username
| password                     | {string}    | optional: password
| options                      | {array}     | optional: initial database options

Example to use sqlite:

```php
return [
    // see https://www.php.net/manual/de/pdo.construct.php
    'dsn' => 'sqlite:'.__DIR__.'/../../../protected/data/my-example-app.sqlite3',
    // 'user' =>'',
    // 'password'=>'',
    // 'options' => []
];
```

👉 **See also**

* `setDatabase(ARRAY)` - initialize a database connection
* `setDebug(BOOL)` - enable/ disable debug output
* `showErrors(BOOL)` - enable/ disable showing errors

### setDatabase()

Initialize a database connection.

```php
$oDB->setDatabase(<ARRAY>);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {array}     | database options

The keys a those for the database connection. See Init -> Database connection config.

🟢 **Return**

bool

* true - on success
* false - on failure.

## Information

### driver()

The driver() Method returns the database type using `getAttribute(PDO::ATTR_DRIVER_NAME)`.

This might be helpful when you want to switch actions in dependency of the database type.

```php
$sDriver = $oDB->driver();
```

🔷 **Parameters**

None.

🟢 **Return**

You get

* a string - if a database was connected; one of "sqlite" or "mysql"
* false - if no database was connected.

### showTables()

Get an array of all database tables as a list. This method abstracts different database types.

showTables
```php
$aTables = $oDB->showTables();
```

🔷 **Parameters**

None.

🟢 **Return**

it returns

* array - list of all database tables
* exception - if no database is connected yet

### tableExists()

You can check if a database table exists. This method abstracts different database types.

```php
$bExists = $oDB->tableExists("mytable");
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | Name of the database table to check

🟢 **Return**

bool:

* true - database table exists
* false - database table doesn't exist

## Execute

### makeQuery()

Execute a database query and get an array as result. It uses `fetchAll(PDO::FETCH_ASSOC)`.

Internally it adds Query, execution time and count of affected rows into a query log.

This method is used by (your) pdo-db objects. If you extend your object with own methods you should use this method to profit from logging and query analysis.

```php
$aData=$oDB->makeQuery(<SQL>, <DATA>, <TABLE>);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | sql query
| 2   | {array}     | optional: data array for prepared statement
| 3   | {string}    | optional: table/ object to be written into query log

🟢 **Return**

It returns

* array with results; remark: other statements than SELECT will return an empty array. Check the result with `is_array()`.
* false - on database exception

👉 **See also**

* `lastquery()` - get the last query only
* `query()` - get an array of all queries

!!! info "Hint"
    For security reasons you should set placeholders `:<field>` in the sql query.
    In the 2nd parameter send an array `[ "<field>" => $value ]`.
    This enables the usage of prepared statements and helps to  prevent sql injections.

**Example**

```php
$sSql = 'SELECT * `mytable` WHERE `id`=:id';
$aData=[
    'id'=>$iId,
];
$aResult = $oDB->makeQuery($sSql, $aData);
```

### dump()

The dump method return and store the create statement and the data of the current database as JSON file. It can save all tables or a subset of given database tables.

The import() method can import this json file.

The import + export methods can be used for

* backup / restore data
* repair (update) database structure if a database column was changed / added
* move data from one database type to another

```php
$aData=$oDB->dump(<FILE>, <TABLES>);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | optional: filename to store
| 2   | {array}     | optional: list of database tables to dump; default: dump all database tables

🟢 **Return**

It returns

* array with results
* false - on error
  * no databses was set
  * unsupported database driver
  * not tables were found
  * output file was given but it was not possible to write data

👉 **See also**

* `import(FILE)` - get the last query only<br>
* `error()` - get the last error

### import()

The import() method can import date that was created by dump().
You should empty or drop all tables before applying the import().

The import will do the following table by table in the given Json file:

* create table<br>If it does not exist yet, it creates the table with the create statement in the dump.
* import all data rows.

This is a good option for just importing backed up data without a change in the object definition for table columns.

```php
$aData=$oDB->import(<FILE>);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | filename to import

🟢 **Return**

It returns

* true - on success
* false - on error
  * no databses was set
  * file does not exist
  * file is no valid json

👉 **See also**

* `dump()` - get the last query only<br>
* `error()` - get the last error

Scenario - Repair a single table:

* use `dump(<FILE>, [<TABLE>])`
* drop the affected table.
* initialize the object with its new column options
* use `import(<FILE>)`. The table now already exists. All data will be imported. This works well if you extend the size of a varchar or add a new column in your object.


### optimize()

This method optimizes the database. In dependency of the database type can be performed a list of queries per database and/ or a list of queries per table.

You should implement this method in a backend for admins only.

```php
$aData=$oDB->optimize();
```

🔷 **Parameters**

None.

🟢 **Return**

It returns

* array with results
* false - on error
  * no databses was set
  * file does not exist
  * file is no valid json

## Debugging

### setDebug()

The object class uses a debug function to write information what it is doing. In your development environment You can enable debugging while initializing the database object with the subkey `showdebug`.

During runtime you can use to enable or disable the debug output.

After enbabling it you see information with gray background between your own output.

!!! warning "Warning"
    In production environments set it to false only!

```php
$oDB->setDebug(true|false);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {bool}      | new debug mode; false = off; true = on

🟢 **Return**

true.

### showErrors()

If an error occurs you automatically can display the error message and details. This is a helper feature for development environment only. 

You can enable debugging globally while initializing the database object with the subkey `showerrors`.

During runtime you can use to enable or disable the debug output with showErrors().

After enbabling it and an error occurs you see messages with red text on yellow background between your own output.

!!! warning "Warning"
    An error can show details of your database.
    In production environments set it to false only!

Hint:

In your application you should test if an action was successful. If it failed then show a userfriendly error message (without technical details).

```php
$oDB->showErrors(true|false);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {bool}      | new error mode; false = off; true = on


🟢 **Return**

bool: true.

## Debug infos

### error()

Get a string with the last error.

!!! warning "Warning"
    Do not print it in production environments!

```php
$sLastError = $oDB->error();
```

🔷 **Parameters**

None.

🟢 **Return**

String.

### lastquery()

Get an array with details of the last query.

!!! warning "Warning"
    Do not print it in production environments!

```php
$aLastQuery = $oDB->lastquery();
```

When setting the parameter to true you get the last failed query (if there is one).

```php
$aLastErrorQuery = $oDB->lastquery(true);
```

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {bool}      | Flag: return query for last error or latest query? true = last error; default: false = latest query

🟢 **Return**

You get

* array with keys below
* false - if there is no query

| Key     | Type       | Description
|---      |:---:       |---
| method  | {string}   | name of the method that triggered the query
| sql     | {string}   | executed sql query
| data    | {array}    | optional: data array (when using prepare statement)
| time    | {float}    | optional: execution time in ms
| records | {integer}  | optional: count of returned records on SELECT or affected rows on INSERT, UPDATE or DELETE
| error   | {string}  | optional: PDO error message

👉 **See also**

* `queries()` - get a list of all queries

### logs()

Get an array of debug logs of (your) pdo-db objects.
You can use it in your dev environment to print a debug table.

!!! warning "Warning"
    Do not dump it in the production environment!

```php
$aLogs = $oDB->logs();
```

🔷 **Parameters**

None.

🟢 **Return**

You get an array with these keys:

| Key      | Type     | Description
|---       |:---:     |---
| loglevel | {string} | loglevel; one of info\|warn\|error
| table    | {string} | table/ object
| method   | {string} | the method where the message comes from
| message  | {string} | the error message

### queries()

Get an array of executed queries of (your) pdo-db objects.
You can use it in your dev environment to print a debug table and 

!!! warning "Warning"
    Do not dump it in the production environment!

```php
$aQueries = $oDB->queries();
```

🔷 **Parameters**

None.

🟢 **Return**

You get an array with these keys:

| Key     | Type       | Description
|---      |:---:       |---
| method  | {string}   | name of the method that triggered the query
| sql     | {string}   | executed sql query
| data    | {array}    | optional: data array (when using prepare statement)
| time    | {float}    | optional: execution time in ms
| records | {integer}  | optional: count of returned records on SELECT or affected rows on INSERT, UPDATE or DELETE
| error   | {string}  | optional: PDO error message

👉 **See also**

* `lastquery()` - get the last query or last failed query only
