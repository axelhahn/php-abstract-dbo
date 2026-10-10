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

Line [122](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L122) (20 lines)

**Return**: `void`

**Parameters**: **2** (required: 2)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sObjectname | `string` | object name to generate a tablename from it
| \<required\> $oDB | `axelhahn\pdo_db` | pdo_db $oDB          instance of database object class

### 🔹 public getTablename()

Get a table name of a given class name
 @see reverse function _getObjectFromTablename()

Line [153](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L153) (4 lines)

**Return**: `string`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $s | `string` | input string to generate a table name from

### 🔹 public makeQuery()

Execute a sql statement
 a wrapper for $this->_pdo->makeQuery() that adds the current table

Line [196](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L196) (5 lines)

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

Line [287](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L287) (102 lines)

**Return**: `array|bool`

**Parameters**: **0** (required: 0)

### 🔹 public new()

Generate a hash for a new empty item

Line [419](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L419) (13 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public create()

Create a new entry in the database

Line [472](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L472) (30 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public read()

Read an entry from database by known row id

Line [509](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L509) (8 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $iId | `int` | row id to read
| \<optional\> $bReadRelations | `bool` | read relation too? default: false

### 🔹 public readByFields()

Read item from row by given fields with AND condition
 Useful for reading item by known uniq single or multiple column values

Line [526](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L526) (67 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aColumns | `array` | 
| \<optional\> $bReadRelations | `bool` | 

### 🔹 public _relGetTargetIds()

Get relations of the current item by given column
 It returns an array with [relation_id => id_of_target_column]

Line [601](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L601) (13 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sCol | `string` | column name

### 🔹 public relSync()

Sync the relations of the current item to the relation table
 - store new relations
 - remove outdated relations

Line [622](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L622) (58 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public update()

Update entry; the field "id" is required to identify a single row in the table
 It returns false if the current item has no changes.
 It returns the id of the object if the update was successful

Line [687](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L687) (28 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public delete()

Delete entry by a given id or current item
 It can be extended using hooks (can be private methods) in your object class.
 Sequence:
 - hookDeletePre() - your optional hook
 - delete relations to the current object
 - hookDelete()    - your optional hook
 - delete item in object

Line [728](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L728) (63 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $iId | `int` | optional: id of the entry to delete; default: 0 (current item)

### 🔹 public flush()

!!! DANGEROUS !!!
 Drop table of current object type. It deletes all items of a type and
 removes the schema from database

Line [803](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L803) (22 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public save()

Save item. If id is set, update. Otherwise create.

Line [830](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L830) (6 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public relCreate()

Create a relation from the current item to an id of a target object

Line [971](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L971) (74 lines)

**Return**: `bool`

**Parameters**: **3** (required: 2)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sToTable | `string` | target object
| \<required\> $iToId | `int` | id of target object
| \<optional\> $sFromColumn | `?string` | optional: source column

### 🔹 public relRead()

Get array with all relations of the current item.
 It can be filtered by a given target
 - by table and optional additionally the target column
 - by a property of current item (that must be a lookup)

 By default it returns information to the relation and the relation target
 By adding filter 'targetonly' => true only the target items of
 subkey "_target" will be returned (without relation).

 Response is a list of such items


```txt
 Array
 (
     [0] => Array
         (
             [id] => 4
                 [timecreated] => 2025-05-14 10:29:20
                 [timeupdated] =>
                 [deleted] => 0
                 [uuid] => a30b5c8daf408c521f24d4efca021547
                 [remark] =>
                 [_column] =>
                 [_totable] => objvideos
                 [_tocolumn] => videofile
                 [_toid] => 3
                 [_target] => Array
                     (
                         [id] => 3
                         [timecreated] => 2025-05-14 10:29:20
                         [timeupdated] => 2025-05-14 16:23:22
                         [deleted] => 0
                         [label] => My video file
                         [videofile] => 3
                         [description] =>
                     )
             )
     )
 ```



 @see relReadLookupItem()

Line [1178](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1178) (30 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aFilter | `array` | optional: filter existing relations by table and column
                          Keys:
                            table      => TARGETTABLE  target table must match
                            column     => COLNAME      target column name must match
                            property   => COLNAME      find related target linked in my given property
                            targetonly => bool         Flag to return not the relation but linked items only

### 🔹 public relReadLookupItem()

Get array with referenced single item of a lookup column
 The response comes from relRead() with option targetonly = true

 @see relRead()

Line [1218](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1218) (17 lines)

**Return**: `array`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sColumn | `string` | column/ property that is a lookup to another table

### 🔹 public relDelete()

Delete a single relation from current item.
 If you want to delete all relations of a single item, use relDeleteAll()

 TODO: check if relation exists in current item

Line [1245](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1245) (10 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $iId | `int` | id of the relation to delete

### 🔹 public relDeleteAll()

Delete all relations of a single item
 called by delete(ID) before deleting the item itself

Line [1262](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1262) (27 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $iId | `int` | if of an item; default: 0 (=current item)

### 🔹 public relFlush()

Delete all relations of current object type.
 Called by flush() before deleting all items of a type.

Line [1295](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1295) (8 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public count()

Get count of existing items

Line [1311](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1311) (5 lines)

**Return**: `int`

**Parameters**: **0** (required: 0)

### 🔹 public get()

Get a single property of an item.
 opposite function of set(KEY, VALUE)

Line [1323](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1323) (8 lines)

**Return**: `mixed`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sKey2Get | `string` | key of your object to set

### 🔹 public getAttributes()

Get array of attribute names

Line [1337](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1337) (6 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bWithValues | `bool` | flag: including values? default: false

### 🔹 public getBasicAttributes()

Get array of main attributes to show in overview or to select a relation

Line [1348](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1348) (26 lines)

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

Line [1385](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1385) (19 lines)

**Return**: `string|bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aItem | `array` | optional: item data; default: current item

### 🔹 public getLabel()

Get a label for the item.
 It fetches the basic attributes if needed.
 Alternatively it uses the id

Line [1414](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1414) (37 lines)

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

Line [1460](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1460) (5 lines)

**Return**: `string|bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sColumn | `string` | name of the lookup column

### 🔹 public getItem()

Get current item as an array

Line [1470](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1470) (4 lines)

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

Line [1503](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1503) (205 lines)

**Return**: `array|bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sAttr | `string` | name of the property

### 🔹 public hasChange()

Get bool if the current dataset item was changed

Line [1713](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1713) (4 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public id()

Get id of the current item as integer
 it returns false if there is no id

Line [1723](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1723) (4 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public getTable()

Get current table

Line [1732](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1732) (4 lines)

**Return**: `string`

**Parameters**: **0** (required: 0)

### 🔹 public search()

Search for items in the current table
 You should use ":<placeholder>" in your sql statements to use
 prepared statements

Line [1754](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1754) (57 lines)

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

Line [1860](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1860) (77 lines)

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

Line [1949](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1949) (26 lines)

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

Line [1983](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-base.class.php#L1983) (20 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aNewValues | `array` | new values to set; a subset of this->_aItem

---
Generated with [Axels PHP class doc parser](https://github.com/axelhahn/php-classdoc)
