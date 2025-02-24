The main class abstracts CRUD actions to handle items of a custom object.

## Objects

* creates a database table per object automatically when using it; colums come from given properties during init
* provides CRUD actions with the methods create() / read(ID) / update / delete() - you don't need to handle sql for your custom objects.
* save() automatically decides to use create() or update()

## Relations

* handles relations between objects - with lookup field to define kind of relation or a loosely coupled relation 
* N:M relations are stored in a separate table - a relation can be seen from both sides

## Logging / debugging

* you can access a list of messages/ warnings/ errors
* you can acces all executed queries with statement, affected rows, needed time etc.
* for development you can let show debug information or database errors.

## More

* detect changes in object definitions that do not match database column settings
* dump and import for backup+restore / repair / transfer to other database or database type.
* This is a non visual component. If you need a web ui to edit your data have look to <https://github.com/axelhahn/axelOM>
