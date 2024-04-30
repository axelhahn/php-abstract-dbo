
## How to use this class

### Introduction

There are 2 abstract classes:

* a class for a PDO object - it creates the database connection
* a class for your data objects

### Create your PDO to connect a database

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

#### Database connection config

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

### Create your object class

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

### Delete an item

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
