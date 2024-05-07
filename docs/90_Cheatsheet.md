## Cheat sheet

### Database object

| method                          | returns   | description
|---                              |:---:      |---
| ``$oDB->setDatabase(ARRAY)``    | {bool}    | create a PDO connection
| DEBUG SETTNGS
| ``$oDB->setDebug(BOOL)``        | {bool}    | enable/ disable debugging
| ``$oDB->showErrors(BOOL)``      | {bool}    | enable/ disable showing errors
| INFOS FOR DEBUGGING
| ``$oDB->error()``               | {string}  | get the last error
| ``$oDB->lastquery(BOOL)``       | {array}   | get an array of last query; if true, then it returns the last db error entry
| ``$oDB->logs()``                | {array}   | get an array of all log messages (errors and others)
| ``$oDB->queries()``             | {array}   | get an array of all queries
| INFOS
| ``$oDB->driver()``              | {string}  | name of the database driver, eg "sqlite", "mysql"
| ``$oDB->tableExists(TABLE)``    | {bool}    | check if a table exists
| EXECUTE
| ``$oDB->makeQuery(SQL, DATA, TABLE)`` | {array}   | Execute a given query and add metadata to log
| ``$oDB->dump()``                | {bool}    | WIP: get an array of all tables and their rows
| ``$oDB->import()``              | {bool}    | WIP: import a given export file

### Item object

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
| ``$o->relRead(FILTER)``         | {array}   | get relations of the current item; FILTER is an optional array with keys "table" and optional "column"
| ``$o->relDelete(RELID)``        | {bool}    | delete a single relation of the current item 
| ``$o->relDeleteAll()``          | {bool}    | delete all relation of the current item 
| ``$o->relDeleteAll(ID)``        | {bool}    | delete all relation of given item 
| ``$o->getRelLabel(COLUMN)``     | {string}  | for 1:1 lookups: get the label of the item in the related lookup table
| MORE DATABASE
| ``$o->flush()``                 | {bool}    | DANGEROUS: delete all items of the current object type by dropping its table
| ``$o->save()``                  | {bool}    | selects automatically create() or update() to store an item
| INFOS
| ``$o->count()``                 | {integer} | get count of existing items for the current item type
| ``$o->getAttributes()``         | {array}   | get list of attributes
| ``$o->getDescriptionline()``    | {string}  | get name string built from main columns
| ``$o->getLabel()``              | {string}  | get name string built from first of main columns (eg. label)
| ``$o->getTable()``              | {string}  | get name of database table for current object
| ``$o->hasChange()``             | {bool}    | check if the current item was changed after applying set() or setItem()
| ``$o->id()``                    | {integer} | get id of current item
| ``$o->search(ARRAY)``           | {array}   | search in objects
| ``$o->verifyColumns()``         | {array}   | verify object definitions with created databse columns
| FORMS
| ``$o->getFormtype(KEY)``        | {string}  | get count of existing items for the current item type

