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
}