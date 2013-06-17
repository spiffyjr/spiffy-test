# SpiffyTest
SpiffyTest is a module for bootstrapping PHPUnit and supplying you with a basic service manager instance.

## Installation
Installation of SpiffyTest uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require spiffy/spiffy-test:0.*
```

1. Copy `spiffy-test/Bootstrap.php.dist` to your `tests/Bootstrap.php`.
2. Copy `spiffy-test/config/test.application.config.php.dist` to your `config/test.application.config.php` and
   modify the `modules` key to include any test dependent modules.
3. Copy `spiffy-test/config/test.module.config.php.dist` to your `config/test.module.config.php.dist` if you have
   test specific module overrides (i.e., removing memcache in favor of array cache).

Installation without composer is not officially supported and requires you to setup autoloading on your own.

## Usage

Using SpiffyTest involves setting up your testing bootstrap, module config, and application config (optional).

1. copy `bootstrap.php.dist` to your `test` directory and rename to `bootstrap.php`. Setup `phpunit.xml` to use this
   as your bootstrap file.
2. copy `module.config.php.dist` to your `test` directory and rename to `module.config.php`. Be sure to leave `SpiffyTest`
   in your list of modules!
3. if you have a custom application.config.php requirement then copy `application.config.php.dist` to your `test` directory
   and rename to `application.config.php`.

Once everything is setup you can access the Module singleton by using `\SpiffyTest\Module::getInstance()`. This class
has helper methods availabe such as `getApplication()`, `getServiceManager()` and `getApplicationConfig()` for testing a mvc stack. You can
reset everything by using the `reset()` method.

### Controllers

SpiffyTest comes with `\SpiffyTest\Controller\AbstractHttpControllerTestCase` which is a customized controller test case
that uses SpiffyTest's application. To use, simply have your tests extend the class.