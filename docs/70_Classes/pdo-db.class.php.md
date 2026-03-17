---
title: axelhahn\pdo_db
generator: Axels php-classdoc; https://github.com/axelhahn/php-classdoc
---

## 📦 Class axelhahn\pdo_db

```txt

 Class for a single PDO connection

 @author hahn

```

## 🔶 Properties

### 🔸 public $db

object of pdo database instance
 @var object

type: object

default value: 

### 🔸 public $_aQueries

executed queries and metadata or error
 @var array

type: array

default value: 



## 🔷 Methods

### 🔹 public __construct()

Constructor - sets internal environment variables and checks existence
 of the database

Line [140](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L140) (21 lines)

**Return**: `void`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aOptions | `array` | array with these keys
                          - cfgfile {string} file name of config file for db connection
                          - db {array} db connection data for PDO with subkeys
                                        - dsn eg. 'mysql:host=addons-web-db;dbname=addons-web;charset=utf8'
                                              or 'sqlite:'.__DIR__.'/../../../protected/data/my-example-app.sqlite3'
                                        - user
                                        - password
                                        - options
                          - showdebug {bool} enable debug? default: false
                          - showerrors {bool} enable error messages? default: false

### 🔹 public _wd()

Write debug output if enabled by flag

Line [172](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L172) (11 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $s | `string` | string to show
| \<optional\> $sTable | `string` | optional: table

### 🔹 public _log()

Add a log message for current object

Line [192](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L192) (20 lines)

**Return**: `bool`

**Parameters**: **4** (required: 4)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sLevel | `string` | loglevel; one of inf|warn|error
| \<required\> $sTable | `string` | table/ object
| \<required\> $sMethod | `string` | the method where the message comes from
| \<required\> $sMessage | `string` | the error message

### 🔹 public setDatabase()

Create a PDO connection

Line [222](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L222) (37 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aOptions | `array` | array with these keys

### 🔹 public setDebug()

Enable/ disable debug; database error is visible on enabled debug only

Line [264](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L264) (11 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $bNewValue | `bool` | new debug mode; false = off; true = on

### 🔹 public showErrors()

Enable/ disable debug; show error message if they occur

Line [281](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L281) (7 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $bNewValue | `bool` | new debug mode; false = off; true = on

### 🔹 public driver()

Get name of the current driver, eg. "mysql" or "sqlite"
 If database is initialized yet it returns false

Line [298](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L298) (4 lines)

**Return**: `string|bool`

**Parameters**: **0** (required: 0)

### 🔹 public getSpecialties()

Get specialties of database properties for creating tables

Line [307](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L307) (4 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public error()

Get the last error message (from a query or a failed method).

 @example:
 to get the last failed database query use lastquery check


```txt if($o->error()) { echo $o->lastquery()['error']}```



 @see lastquery()

Line [322](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L322) (7 lines)

**Return**: `string`

**Parameters**: **0** (required: 0)

### 🔹 public lastquery()

Get the last query as array that can have these keys
   - method  {string}  name of the method that triggered the query
   - sql     {string}  executed sql query
   - data    {array}   optional: data array (when using prepare statement)
   - time    {float}   optional: execution time in ms
   - records {integer} optional: count of returned records on SELECT or affected rows on INSERT, UPDATE or DELETE
   - error   {string}  optional:PDO error message

 @example:
 to get the last failed database query use lastquery check


```txt if($o->error()) { echo $o->lastquery()['error']}```



 @see error()

Line [347](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L347) (7 lines)

**Return**: `array|bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bLastError | `bool` | optional: flag to return the last failed query

### 🔹 public logs()

Get an array with all log messages

Line [359](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L359) (4 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public queries()

Get an array with all queries. Each entry can have these keys:
   - method  {string}  name of the method that triggered the query
   - sql     {string}  executed sql query
   - data    {array}   optional: data array (when using prepare statement)
   - time    {float}   execution time in ms
   - records {integer} count of returned records on SELECT or affected rows on INSERT, UPDATE or DELETE
   - error   {string}  optional:PDO error message

Line [374](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L374) (4 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public tableExists()

Check if a table exists in the current database.

Line [389](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L389) (17 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $table | `string` | Table to search for.

### 🔹 public showTables()

Get an array with all table names

Line [411](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L411) (15 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public makeQuery()

Execute a sql statement and put metadata / error messages into the log

Line [433](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L433) (45 lines)

**Return**: `array|bool`

**Parameters**: **3** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sSql | `string` | sql statement
| \<optional\> $aData | `array` | array with data items; if present prepare statement will be executed
| \<optional\> $_table | `string` | optional: table name to add to log

### 🔹 public optimize()

Optimize database.
 The performed actions for it depend on the database type.

Line [484](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L484) (46 lines)

**Return**: `array|bool`

**Parameters**: **0** (required: 0)

### 🔹 public dump_old()

Dump a database to an array.
 Optional it can write a json file to disk

 @see import()

Line [540](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L540) (55 lines)

**Return**: `array|bool`

**Parameters**: **2** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sOutfile | `string` | optional: output file name
| \<optional\> $aTables | `array` | optional: array of tables to dump; default: false (dumps all tables)

### 🔹 public dump()

Dump a database to an array.
 Optional it can write a json file to disk

 @see import()

Line [621](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L621) (113 lines)

**Return**: `array|bool`

**Parameters**: **2** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sOutfile | `string` | optional: output file name
| \<optional\> $aTables | `array` | optional: array of tables to dump; default: false (dumps all tables)

### 🔹 public import_old()

Import data from a json file; reverse function of dump()
 TODO: handle options array

 @example:
 $aOptions = [
     'global' => [
         'drop' => false,
         'create' => true, // create table if it does not exist
         'import' => true,
     ],
     // when given, only these tables will be imported
     'tables' => [
         'table1' => [
              // optionally: override global settings
             'drop' => false,
             'create-if-not-exists' => true,
             'import' => true,
         ],
         'tableN' => [
             ...
         ]
     ]
 @see dump()

Line [765](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L765) (45 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sFile | `string` | json file to import
| \<optional\> $aOptions | `array` | UNUSED optional: options array with these keys
                               - 'global' {array}  options for all tables
                               - 'tables' {array}  options for all tables

### 🔹 public import()

Import data from a json file; reverse function of dump()

 @example:
 $aOptions = [
     'global' => [
         'drop' => true,
         'create' => true,
         'import' => true,
         'rows2instert' => 30
     ],
     // TODO: add options for each table
     // when given, only these tables will be imported
     'tables' => [
         'table1' => [
              // optionally: override global settings
             'drop' => false,
             'create' => true,
             'import' => true,
         ],
         'tableN' => [
             ...
         ]
     ]
 @see dump()

Line [901](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L901) (157 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sFile | `string` | json file to import
| \<optional\> $aOptions | `array` | UNUSED optional: options array with these keys
                               - 'global' {array}  options for all tables
                                       'drop'   bool  Drop a table before input; default: true
                                       'create' bool  Create a table if not exists; default: true
                                       'import' bool  Import data; default: true
                               - 'tables' {array}  options for all tables - TODO

### 🔹 public dumpAnalyzer()

Analyze given backup file and generate a summary
 If an error occured then the key 'error' will be set
 If OK you get the list of tables and count of datasets to import
 The returned array is like this:

 [
     'file' => <filename>,
     'completed' => <bool>,
     'meta' => <array>,
     'rows' => [
         <table1> => <rowcount>,
         <tableN> => <rowcount>,
      ],
     'counters' => [
         'tables' => <number_of_tables>,
         'roes' => <number_of_rows_total>,
      ],
 ]

 ... or if an error occured:
 [
     'file' => <filename>,
     'completed' => false,
     'error' => <string>,
 ]

Line [1089](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db.class.php#L1089) (51 lines)

**Return**: `array`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sFile | `string` | 

---
Generated with [Axels PHP class doc parser](https://github.com/axelhahn/php-classdoc)
