---
title: axelhahn\pdo_db_attachments
generator: Axels php-classdoc; https://github.com/axelhahn/php-classdoc
---

## 📦 Class axelhahn\pdo_db_attachments

```txt

```

## 🔶 Properties

(none)

## 🔷 Methods

### 🔹 public __construct()

Constructor

Line [43](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L43) (4 lines)

**Return**: `void`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $oDB | `axelhahn\pdo_db` | pdo_db $oDB          instance of database object class

### 🔹 public hookActions()

Get array of all hook actions
 Keys:
   - backend_preview   {string}  method name for preview in AxelOM
   - <any>             {string}  method name four your custom action

Line [63](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L63) (8 lines)

**Return**: `array`

**Parameters**: **0** (required: 0)

### 🔹 public getRelativePath()

Get relative path of a file with full path relative to upload base dir
 Used in storeNewFile()

Line [80](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L80) (4 lines)

**Return**: `string`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $FileWithFullPath | `string` | filename

### 🔹 public uploadFile()

Upload files given by $_FILES from a form and optionally create a
 relation to a given target object

 see pages/object.php

Line [103](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L103) (66 lines)

**Return**: `int|bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aFile | `array` | single array element of $_FILES which means
                      - name      {string} => filename, eg. my-image.png
                      - full_path {string} => ignored
                      - type      {string} => MIME type eg. image/png
                      - tmp_name  {string} => location of uploaded file eg. /tmp/php2hi7k4315in34bgFjGz/tmp/php2hi7k4315in34bgFjGz
                      - error     {int}    => error code, eg 0 for OK
                      - size      {int}    => filesize in byte eg. 312039

### 🔹 public storeNewFile()

Add a file that is located in the upload base path as attachment object

Line [182](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L182) (25 lines)

**Return**: `int|bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sFileWithFullPath | `string` | 
| \<optional\> $aProperties | `array` | array with settings to write; possible keys are
                                   - label
                                   - description
                                   - mime
                                   - width
                                   - height

### 🔹 public attachmentPreview()

Get html code for attachment preview

Line [247](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L247) (49 lines)

**Return**: `string`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aOptions | `array` | array of options; known keys:
                          - baseurl {string}  set base url for attachments that triggers -> setUrlBase(<baseurl>)

### 🔹 public setUploadDir()

Sets the upload directory for the file attachments.

Line [307](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L307) (8 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sDir | `string` | The directory path to set as the upload directory.

### 🔹 public setUrlBase()

Sets the base URL for the file attachments

Line [322](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L322) (4 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sUrl | `string` | The base URL to set.

### 🔹 public getTablename()

Get a table name of a given class name
 @see reverse function _getObjectFromTablename()

Line [151](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L151) (4 lines)

**Return**: `string`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $s | `string` | input string to generate a table name from

### 🔹 public makeQuery()

Execute a sql statement
 a wrapper for $this->_pdo->makeQuery() that adds the current table

Line [194](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L194) (5 lines)

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

Line [285](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L285) (102 lines)

**Return**: `array|bool`

**Parameters**: **0** (required: 0)

### 🔹 public new()

Generate a hash for a new empty item

Line [417](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L417) (13 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public create()

Create a new entry in the database

Line [470](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L470) (30 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public read()

Read an entry from database by known row id

Line [507](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L507) (8 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $iId | `int` | row id to read
| \<optional\> $bReadRelations | `bool` | read relation too? default: false

### 🔹 public readByFields()

Read item from row by given fields with AND condition
 Useful for reading item by known uniq single or multiple column values

Line [524](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L524) (67 lines)

**Return**: `bool`

**Parameters**: **2** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aColumns | `array` | 
| \<optional\> $bReadRelations | `bool` | 

### 🔹 public _relGetTargetIds()

Get relations of the current item by given column
 It returns an array with [relation_id => id_of_target_column]

Line [599](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L599) (13 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $sCol | `string` | column name

### 🔹 public relSync()

Sync the relations of the current item to the relation table
 - store new relations
 - remove outdated relations

Line [620](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L620) (58 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public update()

Update entry; the field "id" is required to identify a single row in the table
 It returns false if the current item has no changes.
 It returns the id of the object if the update was successful

Line [685](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L685) (28 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public delete()

Delete entry by a given id or current item

Line [719](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L719) (50 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $iId | `int` | optional: id of the entry to delete; default: delete current item

### 🔹 public flush()

!!! DANGEROUS !!!
 Drop table of current object type. It deletes all items of a type and
 removes the schema from database

Line [781](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L781) (22 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public save()

Save item. If id is set, update. Otherwise create.

Line [808](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L808) (6 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public relCreate()

Create a relation from the current item to an id of a target object

Line [949](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L949) (74 lines)

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

Line [1155](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1155) (30 lines)

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

Line [1195](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1195) (17 lines)

**Return**: `array`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sColumn | `string` | column/ property that is a lookup to another table

### 🔹 public relDelete()

Delete a single relation from current item.
 If you want to delete all relations of a single item, use relDeleteAll()

 TODO: check if relation exists in current item

Line [1287](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1287) (10 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $iId | `int` | id of the relation to delete

### 🔹 public relDeleteAll()

Delete all relations of a single item
 called by delete(ID) before deleting the item itself

Line [1304](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1304) (24 lines)

**Return**: `bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $iId | `int` | if of an item; default: false (=current item)

### 🔹 public relFlush()

Delete all relations of current object type.
 Called by flush() before deleting all items of a type.

Line [1334](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1334) (8 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public count()

Get count of existing items

Line [1350](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1350) (5 lines)

**Return**: `int`

**Parameters**: **0** (required: 0)

### 🔹 public get()

Get a single property of an item.
 opposite function of set(KEY, VALUE)

Line [1362](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1362) (8 lines)

**Return**: `mixed`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sKey2Get | `string` | key of your object to set

### 🔹 public getAttributes()

Get array of attribute names

Line [1376](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1376) (6 lines)

**Return**: `array`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $bWithValues | `bool` | flag: including values? default: false

### 🔹 public getBasicAttributes()

Get array of main attributes to show in overview or to select a relation

Line [1387](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1387) (26 lines)

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

Line [1424](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1424) (19 lines)

**Return**: `string|bool`

**Parameters**: **1** (required: 0)

| Parameter | Type | Description
|--         |--    |--
| \<optional\> $aItem | `array` | optional: item data; default: current item

### 🔹 public getLabel()

Get a label for the item.
 It fetches the basic attributes if needed.
 Alternatively it uses the id

Line [1453](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1453) (37 lines)

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

Line [1499](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1499) (5 lines)

**Return**: `string|bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sColumn | `string` | name of the lookup column

### 🔹 public getItem()

Get current item as an array

Line [1509](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1509) (4 lines)

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

Line [1542](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1542) (205 lines)

**Return**: `array|bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $sAttr | `string` | name of the property

### 🔹 public hasChange()

Get bool if the current dataset item was changed

Line [1752](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1752) (5 lines)

**Return**: `bool`

**Parameters**: **0** (required: 0)

### 🔹 public id()

Get id of the current item as integer
 it returns false if there is no id

Line [1763](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1763) (4 lines)

**Return**: `int|bool`

**Parameters**: **0** (required: 0)

### 🔹 public getTable()

Get current table

Line [1772](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1772) (4 lines)

**Return**: `string`

**Parameters**: **0** (required: 0)

### 🔹 public search()

Search for items in the current table
 You should use ":<placeholder>" in your sql statements to use
 prepared statements

Line [1794](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1794) (57 lines)

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

Line [1900](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1900) (77 lines)

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

Line [1989](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L1989) (26 lines)

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

Line [2023](https://github.com/axelhahn/php-abstract-dbo/blob/main/src/pdo-db-attachments.class.php#L2023) (20 lines)

**Return**: `bool`

**Parameters**: **1** (required: 1)

| Parameter | Type | Description
|--         |--    |--
| \<required\> $aNewValues | `array` | new values to set; a subset of this->_aItem

---
Generated with [Axels PHP class doc parser](https://github.com/axelhahn/php-classdoc)
