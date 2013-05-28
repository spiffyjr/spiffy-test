<?php

namespace SpiffyTest\Framework;

use SpiffyTest\Module as SpiffyTest;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return SpiffyTest::getInstance()->getServiceManager();
    }

    /**
     * @param string $name
     * @return object
     */
    public function getController($name)
    {
        return $this->getServiceManager()->get('ControllerLoader')->get($name);
    }
}