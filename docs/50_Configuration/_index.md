## Recommendation

Let's start with a file structure. It is not a must to have a similiar structure but I need to show the global picture somehow.

I assume to have a folder `apps`and `vendor` in the document root (or a subdir of it).

```txt
apps
├── <application_1>
│   ├── classes                             (3)
│   │   ├── <object_1>.class.php
│   │   :
│   │   └── <object_N>.class.php
│   │
│   ├── config                              (2)
│   │   └── objects.php
│   ├── data
│   └── files
:
└── <application_N>

vendor/php-abstract-dbo                     (1)
└── src
    ├── pdo-db-attachments.class.php
    ├── pdo-db-base.class.php
    ├── pdo-db-base.constants.php
    ├── pdo-db.class.php
    ├── pdo-db.config.php                   <<<
    ├── pdo-db.config.php.dist
    └── pdo-db-relations.class.php
```

### (1) The pdo-db class

In the folder `vendor/php-abstract-dbo/src/` are all database base classes and a config file for the database connection.

