## Object

Each type of object has its own configuration. Which is a class that extends `pdo_db_base`.

It looks like that:

```php
<?php
namespace axelhahn;

require_once "[...]/php-abstract-dbo/src/pdo-db-base.class.php";

class objaddons extends pdo_db_base{

    /**
     * hash for a table
     * create database column, draw edit form
     * @var array 
     */
    protected array $_aProperties = [
        '<fieldname_1>'       => [<create statement, type, validation options>],
        '<fieldname_N>'       => [<create statement, type, validation options>],
    ];

    public function __construct(object $oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}
```

### Field properties

Each field has a key and a value.


| Key            | type     | default | description |
| ---            | ---      | ---   | --- |
| create         | {string} | -     | Create statement for this property like<br>- integer<br>- varchar(32)<br>- varchar(4096) |
| validate_is    | {string} | -     | Validation rule for this property <br>- "string" value must be a string<br>- "integer" value must be an integer|
| validate_regex | {string} | -     | if set a value must match this regular expression |
| index          | {bool}   | false | Create an index for this column |

#### create

* `TEXT` | `VARCHAR(<N>)` | `DATE` | `DATETIME` | `INT` | `INTEGER` | `NUM` | `REAL` | `TIMESTAMP`

### Example

```php

class objaddons extends pdo_db_base{

    protected array $_aProperties = [
        'label'       => [
            'create' => 'varchar(32)',
            'validate_is'=>'string', 
            'index'=>1,
        ],
        'version' => [
            'create' => 'varchar(32)',
            'validate_is'=>'string', 
            'overview'=>1,
        ],
        'description' => [
            'create' => 'varchar(2046)', 
            'validate_is'=>'string', 
        ],
        'installation' => [
            'create' => 'text', 
            'validate_is'=>'string', 
            'attr'=>[
        ],
        (...)
    ];

    public function __construct(object $oDB)
    {
        parent::__construct(__CLASS__, $oDB);
    }
}        