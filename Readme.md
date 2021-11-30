# punktde/codeception-database

## Gherkin Steps and additional module functions for the Codeception Database module

### How to use

#### Extended module

Use the module `PunktDe\Codeception\Database\Module\Database` instead of the default codeception module `db` in your `codeception.yaml`:

```yaml
modules:
   enabled:
      - PunktDe\Codeception\Database\Module\Database:
         dsn: 'mysql:host=localhost;dbname=testdb'
         user: 'root'
         password: ''
         dump: 'tests/_data/dump.sql'
         populate: true
         cleanup: true
         reconnect: true
         waitlock: 10
         ssl_key: '/path/to/client-key.pem'
         ssl_cert: '/path/to/client-cert.pem'
         ssl_ca: '/path/to/ca-cert.pem'
         ssl_verify_server_cert: false
         ssl_cipher: 'AES256-SHA'
         initial_queries:
             - 'CREATE DATABASE IF NOT EXISTS temp_db;'
             - 'USE temp_db;'
             - 'SET NAMES utf8;'
```


#### Gherkin steps

Just add the trait `PunktDe\Codeception\Database\ActorTraits\Database` to your testing actor. Then you can use `*.feature` files to write your gherkin tests with the new steps.

##### Example actor 

```php
<?php

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;
    use \PunktDe\Codeception\Database\ActorTraits\Database; // use the database steps trait
}
``` 

##### Which steps are there? 

To get all the steps available you can just run the following command:

```bash
vendor/bin/codecept -c path/to/codeception.yaml gherkin:steps suiteName
```

This will give you a table of all the steps available.





