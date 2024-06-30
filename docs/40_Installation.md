## Get the source

In your webroot or project folder create a vendor folder and clone the repository:

```bash
cd <webroot>
cd vendor
git clone https://github.com/axelhahn/php-abstract-dbo.git
```

## Create configuration

In the folder `php-abstract-dbo/src/`: 

Copy `pdo-db.config.php.dist` to `pdo-db.config.php`.

You need to edit the file to setup a database connection. See the configuration chapter for more details.
