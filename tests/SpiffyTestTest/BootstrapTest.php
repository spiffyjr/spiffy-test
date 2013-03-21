<?php

namespace SpiffyTestTest;

use SpiffyTest\Module;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    public function testServiceManagerInitialized()
    {
        $sm = Module::getInstance()->getServiceManager();
        $this->assertInstanceOf('Zend\ServiceManager\ServiceManager', $sm);
    }

    public function testServiceManagerHasApplicationConfig()
    {
        $sm = Module::getInstance()->getServiceManager();
        $this->assertEquals(include __DIR__ . '/../../config/test.application.config.php.dist', $sm->get('ApplicationConfig'));
    }

    public function testModuleManagerHasModuleLoaded()
    {
        $sm = Module::getInstance()->getServiceManager();

        /** @var $mm \Zend\ModuleManager\ModuleManager */
        $mm = $sm->get('ModuleManager');

        $modules = array_keys($mm->getLoadedModules());
        $this->assertEquals('SpiffyTest', $modules[0]);
    }
}