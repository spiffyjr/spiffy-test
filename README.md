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