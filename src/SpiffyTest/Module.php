<?php

namespace SpiffyTest;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Module
{
    /**
     * @var bool
     */
    protected static $loaded = false;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected static $serviceManager;

    /**
     * @return ServiceManager
     */
    public static function getServiceManager()
    {
        static::bootstrap();
        return static::$serviceManager;
    }

    /**
     * Initialize test.
     */
    public static function bootstrap()
    {
        if (self::$loaded) {
            return;
        }

        error_reporting(E_ALL | E_STRICT);

        if (is_readable('../config/test.config.php')) {
            $config = include '../config/test.config.php';
        } else {
            $config = include __DIR__ . '/../../config/test.config.php.dist';
        }

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
        static::$loaded         = true;
    }
}