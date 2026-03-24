---
title: axelhahn\pdo_db_base
generator: Axels php-classdoc; https://github.com/axelhahn/php-classdoc
---

## 📦 Class axelhahn\pdo_db_base

```txt

 class for basic CRUD actions

 @author hahn

```

## 🔶 Properties

(none)

## 🔷 Methods

### 🔹 public __construct()

Constructor - sets internal environment variables and checks existence
 of the database

Line [119](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L119) (20 lines)

**Return**: `void`

**Parameters**: **2** (required: 2)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sObjectname | `string` | object name to generate a tablename from it
| \<required\> $oDB | `axelhahn\pdo_db` | pdo_db $oDB          instance of database object class

### 🔹 public getTablename()

Get a table name of a given class name
 @see reverse function _getObjectFromTablename()

Line [150](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L150) (4 lines)

**Return**: `string`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $s | `string` | input string to generate a table name from

### 🔹 public makeQuery()

Execute a sql statement
 a wrapper for $this->_pdo->makeQuery() that adds the current table

Line [193](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L193) (5 lines)

**Return**: `array|bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sSql | `string` | sql statement
| \<optional\> $aData | `array` | array with data items; if present prepare statement will be executed

### 🔹 public verifyColumns()

Verify database columns with current object configuration. It shows
 - missing columns
 - existing database columns that are not configured
 - columns with wrong type

Line [284](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L284) (102 lines)

**Return**: `array|bool`

**Parameters**: **0** (required: 0)

### 🔹 public new()

Generate a hash for a new empty item

Line [416](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L416) (13 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public create()

Create a new entry in the database

Line [469](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L469) (30 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public read()

Read an entry from database by known row id

Line [506](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L506) (8 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $iId | `int` | row id to read
| \<optional\> $bReadRelations | `bool` | read relation too? default: false

### 🔹 public readByFields()

Read item from row by given fields with AND condition
 Useful for reading item by known uniq single or multiple column values

Line [523](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L523) (67 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aColumns | `array` | 
| \<optional\> $bReadRelations | `bool` | 

### 🔹 public _relGetTargetIds()

Get relations of the current item by given column
 It returns an array with [relation_id => id_of_target_column]

Line [598](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L598) (13 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sCol | `string` | column name

### 🔹 public relSync()

Sync the relations of the current item to the relation table
 - store new relations
 - remove outdated relations

Line [619](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L619) (58 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public update()

Update entry; the field "id" is required to identify a single row in the table
 It returns false if the current item has no changes.
 It returns the id of the object if the update was successful

Line [684](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L684) (28 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public delete()

Delete entry by a given id or current item

Line [718](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L718) (50 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $iId | `int` | optional: id of the entry to delete; default: delete current item

### 🔹 public flush()

!!! DANGEROUS !!!
 Drop table of current object type. It deletes all items of a type and
 removes the schema from database

Line [780](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L780) (22 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public save()

Save item. If id is set, update. Otherwise create.

Line [807](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L807) (6 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public relCreate()

Create a relation from the current item to an id of a target object

Line [948](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L948) (74 lines)

**Return**: `bool`

**Parameters**: **3** (required: 2)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sToTable | `string` | target object
| \<required\> $iToId | `int` | id of target object
| \<optional\> $sFromColumn | `?string` | optional: source column

### 🔹 public relRead()

Get array with all relations of the current item.
 It can be filtered by a given target table and optional column.
 By default it returns information to the relation.

 By adding filter 'targetonly' => true only the target items of
 subkey "_target" will be returned (without relation).

 @see relReadLookupItem()

Line [1122](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1122) (27 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aFilter | `array` | optional: filter existing relations by table and column
                          Keys:
                            table      => TARGETTABLE  target table must match
                            column     => COLNAME      column name must match
                            targetonly => bool         Flag to return not the relation but linked items only

### 🔹 public relReadLookupItem()

Get array of referenced item of a lookup column

Line [1156](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1156) (17 lines)

**Return**: `array`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sColumn | `string` | name column that is a of the lookup column to another table

### 🔹 public relDelete()

Delete a single relation from current item

Line [1244](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1244) (12 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $iId | `int` | optional: id of the relation to delete

### 🔹 public relDeleteAll()

Delete all relations of a single item
 called by delete(ID) before deleting the item itself

Line [1263](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1263) (24 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $iId | `int` | if of an item; default: false (=current item)

### 🔹 public relFlush()

Delete all relations of current object type.
 Called by flush() before deleting all items of a type.

Line [1293](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1293) (8 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public count()

Get count of existing items

Line [1309](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1309) (5 lines)

**Return**: `int`

**Parameters**: **0** (required: 0)

### 🔹 public get()

Get a single property of an item.
 opposite function of set(KEY, VALUE)

Line [1321](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1321) (8 lines)

**Return**: `mixed`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sKey2Get | `string` | key of your object to set

### 🔹 public getAttributes()

Get array of attribute names

Line [1335](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1335) (6 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bWithValues | `bool` | flag: including values? default: false

### 🔹 public getBasicAttributes()

Get array of main attributes to show in overview or to select a relation

Line [1346](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1346) (26 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bWithSortkey | `bool` | 

### 🔹 public getDescriptionLine()

Get a single line for a database row description

 It fetches the basic attributes of the item and creates a single line string
 with values of the item, separated by dashes.
 If the item has no data, it returns false.

Line [1383](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1383) (19 lines)

**Return**: `string|bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aItem | `array` | optional: item data; default: current item

### 🔹 public getLabel()

Get a label for the item.
 It fetches the basic attributes if needed.
 Alternatively it uses the id

Line [1412](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1412) (37 lines)

**Return**: `string`

**Parameters**: **2** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aItem | `array` | optional: item data; default: current item
| \<optional\> $aColumns | `array` | optional: array of columns to show; default: basic attributes

### 🔹 public getRelLabel()

For 1:1 lookups: get the label of the related item by a given column.
 It fetches the current value of the column and returns the label of the
 connected item of the lookup table

Line [1458](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1458) (5 lines)

**Return**: `string|bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sColumn | `string` | name of the lookup column

### 🔹 public getItem()

Get current item as an array

Line [1468](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1468) (4 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public getFormtype()

Return or guess the form type of a given attribute
 If $this->_aProperties[$sAttr]['attr'] was defined then it returns that value.
 Otherwise the type will be guessed based on the attribute name or create statement.

 Guess behaviour by create statement
 - text -> textarea
 - varchar -> input type text; maxsize is size of varchar
 - varchar with more than 1024 byte -> textarea

 If attribute starts with
   - "color"    -> input with type "color"
   - "date"     -> input with type "date"
   - "datetime" -> input with type "datetime-local"
   - "email"    -> input with type "email"
   - "html"     -> textarea with type "html"
   - "month"    -> input with type "month"    !! check browser compatibility
   - "number"   -> input with type "number"
   - "password" -> input with type "password" !! additional logic required
   - "range"    -> input with type "range"
   - "tel"      -> input with type "tel"
   - "time"     -> input with type "time"
   - "url"      -> input with type "url"
   - "week"     -> input with type "week"     !! check browser compatibility

Line [1501](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1501) (205 lines)

**Return**: `array|bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sAttr | `string` | name of the property

### 🔹 public hasChange()

Get bool if the current dataset item was changed

Line [1711](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1711) (5 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public id()

Get id of the current item as integer
 it returns false if there is no id

Line [1722](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1722) (4 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public getTable()

Get current table

Line [1731](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1731) (4 lines)

**Return**: `string`

**Parameters**: **0** (required: 0)

### 🔹 public search()

Search for items in the current table
 You should use ":<placeholder>" in your sql statements to use
 prepared statements

Line [1753](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1753) (57 lines)

**Return**: `array|bool`

**Parameters**: **2** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aOptions | `array` | array with search options
                          - columns - array|string
                          - where   - array|string
                          - order   - array|string
                          - limit   - string
| \<optional\> $aData | `array` | array with values for prepared statement

### 🔹 public validate()

Validate a new value to be set on a property and return bool for success
 - The general fields (id, timecreated, timeupdated, delete) cannot be set.
 - validate a field if validate_is set a type or auto detected by "create" key in property
 - validate a field if validate_regex set regex
 This method is called in set() but can be executed on its own

 @see set()
 @throws \Exception

Line [1859](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1859) (77 lines)

**Return**: `bool`

**Parameters**: **2** (required: 2)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sKey2Set | `string` | key of your object to set
| \<required\> $value | `mixed` | new value to set

### 🔹 public set()

Set a single property of an item.
 - The general fields (id, timecreated, timeupdated, delete) cannot be set.
 - validate a field if validate_is set a tyoe
 - validate a field if validate_regex set regex
 Opposite function of get()

Line [1948](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1948) (26 lines)

**Return**: `bool`

**Parameters**: **2** (required: 2)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sKey2Set | `string` | key of your object to set
| \<required\> $value | `mixed` | new value to set

### 🔹 public setItem()

Set new values for an item.
 The general fields (id, created, updated, delete) cannot be set.
 Opposite function if getItem()

Line [1982](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1982) (20 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aNewValues | `array` | new values to set; a subset of this->_aItem

---
Generated with [Axels PHP class doc parser](https://github.com/axelhahn/php-classdoc)
