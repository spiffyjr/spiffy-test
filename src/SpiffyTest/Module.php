<?php

namespace SpiffyTest;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;

// for backwards compatability, will be removed in future
require_once __DIR__ . '/../../autoload.php';

class Module implements ConfigProviderInterface
{
    /**
     * @var Module
     */
    protected static $module = null;

    /**
     * @var \Zend\Mvc\Application
     */
    protected $application;

    /**
     * Static method to get instance.
     *
     * @return Module
     */
    public static function getInstance()
    {
        if (null === self::$module) {
            self::$module = new Module();
        }
        return self::$module;
    }

    /**
     * @return array
     */
    public function getApplicationConfig()
    {
        return $this->getServiceManager()->get('ApplicationConfig');
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->getApplication()->getServiceManager();
    }

    /**
     * Reset to clean start.
     */
    public function reset()
    {
        $this->application = null;
    }

    /**
     * Initialize test.
     */
    public function bootstrap()
    {
        if ($this->application) {
            return;
        }

        error_reporting(E_ALL | E_STRICT);

        if (is_readable('./application.config.php')) {
            $config = include './application.config.php';
        } else {
            $config = include __DIR__ . '/../../application.config.php.dist';
        }

        if (is_readable('./module.config.php')) {
            $config['module_listener_options']['config_glob_paths'][] = './module.config.php';
        }

        $this->application = Application::init($config);
    }

    /**
     * @return \Zend\Mvc\Application
     */
    public function getApplication()
    {
        $this->bootstrap();
        return $this->application;
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}
