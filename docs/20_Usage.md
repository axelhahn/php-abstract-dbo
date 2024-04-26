
## How to use this class ##

### Introduction ###

There are 2 abstract classes:

* a class for a PDO object - it creates the database connection
* a class for your data objects

### Create your PDO to connect a database  ###

```php
<?php
require_once __DIR__.'/vendor/php-abstract-dbo/src/pdo-db.class.php';
$oDB=new axelhahn\pdo_db([
    'showdebug'=>true,
    'showerrors'=>true,
]);
if (!$oDB->db){
    echo $oDB->error().'<br>';
    die("SORRY, unable to connect the database.");
}
```

Keys for the pdo_db constructor

| Key                          | Type        | Description
|---                           |:---:        |---
| cfgfile                      | {string}    | set file with path to a config to create a PDO connection; default: ``pdo-db.config.php`` in the ./vendor/php-abstract-dbo/src/ directrory
| showdebug                    | {bool}      | enable debugging
| showerrors                   | {bool}      | enable to show any error

#### Database connection config ####

The config file contains the keys

| Key                          | Type        | Description
|---                           |:---:        |---
| dsn                          | {string}    | a dsn; see also: https://www.php.net/manual/de/pdo.construct.php
| user                         | {string}    | optional: username
| password                     | {string}    | optional: password
| options                      | {array}     | optional: initial database options


```php
return [
    // see https://www.php.net/manual/de/pdo.construct.php
    'dsn' => 'sqlite:'.__DIR__.'/../../../protected/data/my-example-app.sqlite3',
    'user' =>'',
    'password'=>'',
    'options' => []
];
```

### Create your object class ###

This is an example class "objexample" with 2 properties "label" and "description":

```php
<?php
namespace axelhahn;
require_once __DIR__."/../vendor/php-abstract-dbo/pdo-db-base.class.php";

class objexample extends pdo_db_base{

    /**
     * hash for a table
     * create database column, draw edit form
     * @var array 
     */
    protected $_aProperties = [
        'label'       => ['create' => 'TEXT',],
        'description' => ['create' => 'TEXT',],
    ];

    public function __construct($oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}
```

See [Sqlite datatypes](https://www.sqlite.org/datatype3.html)

| Key                          | Type        | Description
|---                           |:---:        |---
| create                       | {string}    | column definition for the sql CREATE statement
| validate_is                  | {string}    | optional; if set a item value is checked: validate type - one of string|integer
| validate_regex               | {string}    | optional; if set a item value is checked: validate regey - eg. ``'/^[a-z]*$/'``

### Add in your code

In your php code:

```php

// initialize database conection
require_once __DIR__.'/vendor/php-abstract-dbo/pdo-db.class.php';
$oDB=new axelhahn\pdo_db();

// initialize your objexample class
require __DIR__.'/classes/obj-example.class.php';
$o=new axelhahn\objexample($oDB);

// echo "- found records: " . $o->count()."<br>";

```

### Cheat sheet

#### Database object

| method                          | returns   | description
|---                              |:---:      |---
| ``$oDB->setDatabase(ARRAY)``    | {bool}    | create a PDO connection
| ``$oDB->setDebug(BOOL)``        | {bool}    | enable/ disable debugging
| ``$oDB->showErrors(BOOL)``      | {bool}    | enable/ disable showing errors
| ``$oDB->driver()``              | {string}  | name of the database driver
| ``$oDB->error()``               | {string}  | get the last error
| ``$oDB->lastquery()``           | {array}   | get an array of last query
| ``$oDB->logs()``                | {array}   | get an array of all log messages (errors and others)
| ``$oDB->queries()``             | {array}   | get an array of all queries
| ``$oDB->dump()``                | {array}   | get an array of all tables and their rows (import will follow soon)

#### Item object

| method                          | returns   | description
|---                              |:---:      |---
| ITEM ACTIONS
| ``$o->new()``                   | {bool}    | create a blank new item
| ``$o->get(KEY)``                | {variant} | get a single attribute
| ``$o->getItem()``               | {variant} | get current item as array
| ``$o->set(KEY, VALUE)``         | {bool}    | set a single attribute
| ``$o->setItem(ARRAY)``          | {bool}    | set an array as item
| CRUD
| ``$o->create()``                | {bool}    | store a newly created item into database
| ``$o->read(ID,[FLAG])``         | {bool}    | read attribute with ID from database; you can read relations or use relRead() later
| ``$o->update()``                | {bool}    | updare an existnig item in the database
| ``$o->delete()``                | {bool}    | delete current item in the database
| ``$o->delete(ID)``              | {bool}    | delete item with given ID in the database
| RELATIONS
| ``$o->relCreate(TABLE, ID)``    | {bool}    | create a relation between current item and an id to another table
| ``$o->relRead()``               | {array}   | get relations of the current item
| ``$o->relDelete(RELID)``        | {bool}    | delete a single relation of the current item 
| ``$o->relDeleteAll()``          | {bool}    | delete all relation of the current item 
| ``$o->relDeleteAll(ID)``        | {bool}    | delete all relation of given item 
| MORE DATABASE
| ``$o->flush()``                 | {bool}    | DANGEROUS: delete all items of the current object type by dropping its table
| ``$o->save()``                  | {bool}    | selects automatically create() or update() to store an item
| INFOS
| ``$o->getAttributes()``         | {array}   | get list of attributes
| ``$o->count()``                 | {integer} | get count of existing items for the current item type
| ``$o->id()``                    | {integer} | get id of current item
| ``$o->search(ARRAY)``           | {array}   | search in objects
| ``$o->verifyColumns()``         | {array}   | verify object definitions with created databse columns
| FORMS
| ``$o->getFormtype(KEY)``        | {string}  | get count of existing items for the current item type


### Set value

Variant 1:

With the method "set(PROPERTY, VALUE)" you can modify the current value of a single property.
The method save() detects if the current item is new or not and executes create() or update().

```php
// create a new blank object; optional - it is done in the constructor
$o->new();

// set values
$o->set('label', 'test'.($iCount+1));
$o->set('description', 'test object #'.($iCount+1));
$o->save();
```

Variant 2:

You can get the item into a variable and modify its values. 
Write it back by using method "setItem(ITEM)" (it uses multiple set() and then calls save()). With setItem() you also can add an array with modified properties only.

```php

$o->new();

// get current item
$aItem=$o->getItem();

/*
returns 
Array
(
    [id] => automatic            <<< default columns for each object type
    [timecreated] => automatic
    [timeupdated] => automatic
    [deleted] => 0

    [label] =>                    <<< the properties of our object
    [description] => 
)
*/

$aItem['label']='test'.($iCount+1);
$aItem['description']='test object #'.($iCount+1);
$o->setItem($aItem);
```

### Read an item

You need an id to read an object from database.

```php
$o->read(25);
```

This you can modify and save (see above).


### Getter

Get the current object into a hash

```php
$aItem=$o->getItem();
```


get a single value

```php
// $o->get([KEY]);
$o->get('label');
```

### delete an item

To delete a loaded item use

```php
$o->delete();
```

After this the method getItem() will return a default item.

You also can delete an item with a known id:

```php
$o->delete(ID);
```

### Search

This is an example search

```php
$aData=$o->search(array(
    'columns'=>'*',
    'where'=>["label like 'test2%' "],
    'order'=>[
        'label ASC',
        'timecreated ASC'
    ],
    'limit'=>'0,3'
));

echo '<pre>'; print_r($aData); echo '</pre>';
```

```text
array with search options
- columns - array|string
- where   - array|string
- order   - array|string
- limit   - string
```

### relCreate

The current item must be saved (to get an id) and then it can create a relation to a given table and its id.

```php
$o->relCreate('objlanguages', 1);
```
