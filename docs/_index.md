
# create objects 

Free software and Open Source

📄 Source:
📜 License: GNU GPL 3.0 \
📖 Docs: 

- - -

## About this class ##

The main class abstracts CRUD actions to handle items of a custom object.
It 

* creates a database table per object; colums come from given properties during init
* provides CRUD actions with the methods create() / read(ID) / update / delete()
* save() automatically decides to use create() or update()
* handles relations between objects
* relations are stored in a separate table - so a relation can be seen from both sides
* For debugging:
  * you can access a list of messages/ warnings/ errors
  * you can acces all executed queries with statement, count of results, needed time etc.