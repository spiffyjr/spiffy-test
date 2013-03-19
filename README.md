# SpiffyTest
SpiffyTest is a module for bootstrapping PHPUnit and supplying you with a basic service manager instance.

## Installation
Installation of SpiffyTest uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require spiffy/spiffy-test:0.*
```

1. Copy `spiffy-test/Bootstrap.php.dist` to your `tests/Bootstrap.php`.
2. Copy `spiffy-test/config/test.config.php.dist` to your `config/test.config.php` and modify the `modules` key.

Installation without composer is not officially supported and requires you to setup autoloading on your own.