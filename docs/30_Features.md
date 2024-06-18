The main class abstracts CRUD actions to handle items of a custom object.

## Objects

* creates a database table per object; colums come from given properties during init
* provides CRUD actions with the methods create() / read(ID) / update / delete() - you don't need to handle sql.
* save() automatically decides to use create() or update()

## Relations

* handles relations between objects
* N:M relations are stored in a separate table - a relation can be seen from both sides

## Debugging

* you can access a list of messages/ warnings/ errors
* you can acces all executed queries with statement, affected rows, needed time etc.

## More

* detect changes in object definitions that do not match database column settings
* dump and import for backup+restore / repair / transfer to other database
* This is a non visual component. If you need a web ui to edit your data have look to <https://github.com/axelhahn/axelOM>