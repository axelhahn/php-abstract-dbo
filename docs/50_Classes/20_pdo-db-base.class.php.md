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

Create a new item for the current object type.

🔷 **Parameters**

None

🟢 **Return**

bool: true

👉 **See also**

* `getItem()` - to get the current item as array
* `set(KEY, VALUE)` - set a single property
* `create()` - store new item into database

### get(KEY)

Get a single property of the current item.
After read(ID) you can read the value.
A new item can have a value only if you set the property with set(KEY, VALUE).

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | property to read

🟢 **Return**

Mixed.

The return value depends on the property and its set type in the object configuration.

### getItem()

Get the current item as array.
After new() or read(ID) you get an array with all its properties as keys.

🔷 **Parameters**

None

🟢 **Return**

Array.


### set()

Set a property of an item.
You need to give a property name and its new value.

If you set a validation rule it will be checked. You get false as return value if the validation failed.

**Remark**: Keep in mind that you also need to store the changed item into the database after changing all properties.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | property to set
| 2   | {mixed}     | new value

🟢 **Return**

bool

* true - on success
* false - on failure.

### setItem()

Set multiple values of an item.
You need to give an array with property namen and their new values.

If you set a validation rule it will be checked. You get false as return value if the validation of a property failed.

**Remark**: Keep in mind that you also need to store the changed item into the database after changing all properties.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {array}     | array of properties to set

🟢 **Return**

bool: true

👉 **See also**

* `getItem()` - get the current item as array \
* `set(KEY, VALUE)` - set a single property

## CRUD

### create()

Create a new row in the database with the current item.
If your item has a set id it will be ignored and you create an additional row.

🔷 **Parameters**

None.

🟢 **Return**

bool|integer false on failure or new id on success

bool: true

👉 **See also**

You need to create the item first and set its properties.

* `new()` - get the current item as array

Followed by

* `set(KEY, VALUE)` - set a single property 
or
* `getItem()` - get the current item as array
* `setItem(ARRAY)` - set an item from array

### read()

Read a database row by id and load it as current item.
You can use `getItem()` to get all properties and values as array.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {integer}   | id of the item to read
| 2   | {bool}      | flag: set to true to read relations too (or: use relRead() later)

🟢 **Return**

bool: 

* true - success. The given id was found and the current item was set with data of the found row.
* false - failed. The id was not found. The current item is empty (like after new())

### update()

Save the current item in the database. The value "id" is required. If it is a new item then you must use create() ... or you use save() for automatic switch between create() and update().

Next to the current item all relations of it will be updated too.

An item won't be saved if it has no changes.

🔷 **Parameters**

None.

🟢 **Return**

bool:

* true - a modified item was changed
* false - object was not modified or update failed.

### delete()

Delete the item in the database. The value "id" can be given to delete the item with the given id. If no id was given then the current item will be deleted.

Next to the item all its relations of it will be deleted too.


🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {integer}   | optional: id of the entry to delete; default: delete current item

🟢 **Return**

bool:

* true - the item was deleted
* false - failed: no id or deletion failed

## RELATIONS

All relations will be handled in an extra table. It automatically will be created when using this class.

You always can link an item with the id of another (or the same) object. That a relation exists between 2 objects you will see from both objects (nice, right?).

It is allowed to create a single unspecific link between 2 objects. You have no description what kind of relation it is.

As an additional method you can define a property as integer that is a lookup to another object. When using update() it will handle the relation table automatically. You should use the lookup if you need to specify a role for the link or you need multiple links to an object with a different roles.

### relCreate()

Create a relation between current item and an id of another table (object).

If your current object has a property as integer and is defined as lookup to a specified table than the property name will be set. The update() wil fill in this by itself.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | target object
| 2   | {integer}   | id of target object
| 3   | {string}    | optional: source column

🟢 **Return**

bool

* true - success. The relation was created.
* false - failed. 
  * no id exists for current item (use create() first)
  * no target tale was given
  * target table does not exist
  * id of target table was not given
  * the relation already exists
  * unable to save the relation

### relRead()

Get an array of the relations of the current object to other elements.
Without filter you get all relations. 
A filter can limit the relations to a given table and an optional column.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {array}     | optional: filter existing relations by table and column<br>Keys:<br>- table => <TARGETTABLE>  table must match<br>- column => <COLNAME>     column name must match too

🟢 **Return**

Array

### relUpdate()

Update a single relation from current item - set another id of the currently connected object.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | key of the relation; a string like 'table:id'
| 2   | {integer}   | new id to on target db

🟢 **Return**

Bool

* true - success. The relation was updated.
* false
  * if given relation key does not exist
  * update of relation failed

### relDelete()

Delete a single relation from current item.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | key of the relation; a string like 'table:id'

🟢 **Return**

Bool

* true - success. The relation was deleted.
* false
  * if given relation key does not exist
  * deletion of relation failed

### relDeleteAll()

Delete all relations of a single item. Without parameter it deletes all relations of the current item. Given an integer to delete relations of another item of the same object type.

It is called by delete(ID) before deleting the item itself.

!!! danger "Danger"
    This function is function you should use with caution!

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {integer}   | if of an item; default: false (=current item)

🟢 **Return**

Bool.

* true - success.
  * the object has no relations
  * deletion of all relations were done
* false
  * deletion of a relation failed

### relFlush()

Delete all relations of all objects of the current object type(!).
Called by flush() before deleting all items of a type.

!!! danger "Danger"
    This function is function you should use with caution!
    It is useful if you really want to delete all objects of the current type.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {integer}   | if of an item; default: false (=current item)

🟢 **Return**

Bool.

* true - deletion was done
* false - min. one deletion query failed

### getRelLabel(COLUMN)

For 1:1 lookups: get the label of the related item by a given column.
It fetches the current value of the column and returns the label of the connected item of the lookup table

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | name of the lookup column

🟢 **Return**

String.

## MORE DATABASE

### flush()

!!! danger "Danger"
    This function is function you should use with caution!

Drop table of current object type. It deletes all items of a type and removes the schema from database

🔷 **Parameters**

None.

🟢 **Return**

Boolean.

* true - success: the table was flushed
* false - failed
  * a relation was not deleted
  * drop table failed

### save()

Save item. If id is set, update. Otherwise create.

🔷 **Parameters**

None.

🟢 **Return**

Boolean. Result of create() or update().

### search()

Search for items in the current table.
You should use ":<placeholder>" in your sql statements to use prepared statements

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {array}     | array with search options<br>- columns - array\|string<br>- where   - array\|string<br>- order   - array\|string<br>- limit   - string<br>
| 2   | {array}     | array with values for prepared statement

🟢 **Return**

Boolean|array.

✏️ **Example**

A search with all search options in param 1:

```php
$aData=$o->search([
    'columns'=>'*',
    'where'=>["label like 'test2%' "],
    'order'=>[
        'label ASC',
        'timecreated ASC'
    ],
    'limit'=>'0,3'
]);
```

Get data with basic attributes of the current object type:

```php
$aBasicAttributes = $o->getBasicAttributes();
$aItems = $o->search(['columns'=>$aBasicAttributes]);
```

## INFOS

### count()

Get count of existing items in current object type.

🔷 **Parameters**

None.

🟢 **Return**

Integer.

### getAttributes()

Get array of attribute names.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {bool}      | flag: including values? default: false

🟢 **Return**

Array.

* Array with list of properties
* Hash with key - value data of attributes

### getDescriptionline()

Get a single line for a database row description.

It fetches the basic attributes of the item and creates a single line string with values of the item, separated by dashes.
If the item has no data, it returns false.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {array}     | optional: item data; default: current item

🟢 **Return**

Bool|String.

### getLabel()

Get a label for the item.
It fetches the basic attributes if needed. Alternatively it uses the id.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {array}     | optional: item data; default: current item
| 2   | {array}     | optional: array of columns to show; default: basic attributes

🟢 **Return**

Bool|String.

### getTable()

Get the database tablename for the current object type.

🔷 **Parameters**

None.

🟢 **Return**

String.

### hasChange()

Get bool if the current dataset item was changed.

🔷 **Parameters**

None.

🟢 **Return**

Bool.

* true - one of the properties was changed
* false - no change

### id()

Get id of the current item as integer.
It returns false if there is no id.


🔷 **Parameters**

None.

🟢 **Return**

Bool|Integer.

### verifyColumns()

Verify database columns with current object configuration. It shows

* missing columns
* existing database columns that are not configured
* columns with wrong type

🔷 **Parameters**

None.

🟢 **Return**

Bool|Array.

TODO: example output

## FORMS

### getFormtype(KEY)

Return or guess the form type of a given attribute.

This method is for rendering the form field of an object admin. The focus is on usage of AdminTE in <https://github.com/axelhahn/axelOM>.

If `$this->_aProperties[$sAttr]['attr']` was defined then these html properties will be applied.

Then: If `$this->_aProperties[$sAttr]['force']` was defined then it returns that value.
Otherwise the type will be guessed based on the attribute name or create statement.

If the property is an integer and you set a lookup to an object:

* A select box will be created
* options will be fetched from given target object
* with "bootstrap-select" you can create a select box that can be filtered (using the "bootstrap-select" plugin)

Otherwise: Guess behaviour by create statement

* int|integer -> input type integer
* text -> textarea
* varchar -> input type text; maxsize is size of varchar
* varchar with more than 1024 byte -> textarea

If attribute starts with 

* "date"     -> input with type date
* "datetime" -> input with type datetime-local
* "html"     -> textarea with type "html"
* "number"   -> textarea with type "number"

The return value is an array with form attributes to be rendered and a key "debug" with explainations why the decision was made and how.

🔷 **Parameters**

| #   | Type        | Description
|:---:|:---:        |---
| 1   | {string}    | name of the property

🟢 **Return**

Bool|Array.

* false - the property does not exist
* array with keys "debug" + "tag" and html attributes

TODO: here is a lot of magic. We need some examples - or set a reference to to docs of axel:OM.
