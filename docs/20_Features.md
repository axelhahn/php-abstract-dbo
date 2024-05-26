The main class abstracts CRUD actions to handle items of a custom object.
It 

* creates a database table per object; colums come from given properties during init
* provides CRUD actions with the methods create() / read(ID) / update / delete()
* save() automatically decides to use create() or update()
* handles relations between objects
* relations are stored in a separate table - so a relation can be seen from both sides
* detect changes in object definitions that do not match database column settings
* dump and import for backup+restore / repair / transfer to other database
* For debugging:
  * you can access a list of messages/ warnings/ errors
  * you can acces all executed queries with statement, affected rows, needed time etc.
