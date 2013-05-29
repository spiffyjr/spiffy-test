<?php

namespace SpiffyTest\Controller;

use SpiffyTest\Module as SpiffyTest;
use Zend\Console\Console;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase as BaseController;

class AbstractHttpControllerTestCase extends BaseController
{
    /**
     * @var \Zend\Mvc\Application
     */
    protected $spiffyApplication;

    /**
     * @return \Zend\Mvc\Application
     */
    public function getApplication()
    {
        if ($this->spiffyApplication) {
            return $this->spiffyApplication;
        }

        Console::overrideIsConsole($this->getUseConsoleRequest());
        $this->spiffyApplication = SpiffyTest::getInstance()->getApplication();
        $events = $this->spiffyApplication->getEventManager();
        $events->detach($this->spiffyApplication->getServiceManager()->get('SendResponseListener'));

        return $this->spiffyApplication;
    }

    /**
     * @param string $name
     * @return AbstractActionController
     */
    public function getController($name)
    {
        return $this->getApplicationServiceLocator()
                    ->get('ControllerLoader')
                    ->get($name);
    }

    /**
     * @return \Zend\Test\PHPUnit\Controller\AbstractControllerTestCase
     */
    public function reset()
    {
        $this->spiffyApplication = null;
        SpiffyTest::getInstance()->reset();

        return parent::reset();
    }
}