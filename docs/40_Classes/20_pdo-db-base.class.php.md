## Introduction

Imagine all your objects of a wanted type are stored in a database.

So an object type is connected to a database table and all its entries are handled by rows.

A custom class for a specific object defines its wanted database columns. A column name is the property of the object.

The file `pdo-db-base.class.php` contains several basic methods including the CRUD fnctionality and handle relations between objects. This class will be extended by your pdo-objects to inherit all the methods.

## Create a custom object

This is a minimal example class "objexample" with 2 properties "label" and "description":

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

    public function __construct(object $oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}
```

👉 See [Sqlite datatypes](https://www.sqlite.org/datatype3.html)

| Key                          | Type        | Description
|---                           |:---:        |---
| create                       | {string}    | column definition for the sql CREATE statement
| validate_is                  | {string}    | optional; if set a item value is checked: validate type - one of string|integer
| validate_regex               | {string}    | optional; if set a item value is checked: validate regey - eg. ``'/^[a-z]*$/'``

## Item actions

### new()
| $o->new()                   | {bool}    | create a blank new item

## get(KEY)
| $o->get(KEY)                | {variant} | get a single attribute


### getItem()
| $o->getItem()               | {variant} | get current item as array


### set()
| $o->set(KEY, VALUE)         | {bool}    | set a single attribute

### setItem()
| $o->setItem(ARRAY)          | {bool}    | set an array as item


## CRUD

### create()
| $o->create()                | {bool}    | store a newly created item into database

### read()
| $o->read(ID,[FLAG])         | {bool}    | read attribute with ID from database; you can read relations or use relRead() later

### update()
| $o->update()                | {bool}    | updare an existnig item in the database

### delete()
| $o->delete()                | {bool}    | delete current item in the database

## RELATIONS

### relCreate()
| $o->relCreate(TABLE, ID)    | {bool}    | create a relation between current item and an id to another table

### relRead()
| $o->relRead(FILTER)         | {array}   | get relations of the current item; FILTER is an optional array with keys "table" and optional "column"

### relDelete()
| $o->relDelete(RELID)        | {bool}    | delete a single relation of the current item 

### relDeleteAll()
| $o->relDeleteAll(ID)        | {bool}    | delete all relation of given item 
| $o->relDeleteAll(ID)        | {bool}    | delete all relation of given item 

### getRelLabel(COLUMN)
| $o->getRelLabel(COLUMN)     | {string}  | for 1:1 lookups: get the label of the item in the related lookup table

## MORE DATABASE
### flush()
| $o->flush()                 | {bool}    | DANGEROUS: delete all items of the current object type by dropping its table

### save()
| $o->save()                  | {bool}    | selects automatically create() or update() to store an item

### search()
| $o->search(ARRAY)           | {array}   | search in objects

## INFOS

### count()
| $o->count()                 | {integer} | get count of existing items for the current item type

### getAttributes()
| $o->getAttributes()         | {array}   | get list of attributes

### getDescriptionline()
| $o->getDescriptionline()    | {string}  | get name string built from main columns

### getLabel()
| $o->getLabel()              | {string}  | get name string built from first of main columns (eg. label)

### getTable()
| $o->getTable()              | {string}  | get name of database table for current object

### hasChange()
| $o->hasChange()             | {bool}    | check if the current item was changed after applying set() or setItem()

### id()
| $o->id()                    | {integer} | get id of current item

### verifyColumns()
| $o->verifyColumns()         | {array}   | verify object definitions with created databse columns

## FORMS
### getFormtype(KEY)
| $o->getFormtype(KEY)        | {string}  | get count of existing items for the current item type

