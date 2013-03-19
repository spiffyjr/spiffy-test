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

        // Custom application.config.php
        if (is_readable('../config/test.application.config.php')) {
            $config = include '../config/test.application.config.php';
        } else {
            $config = include __DIR__ . '/../../config/test.application.config.php.dist';
        }

        // Custom module.config.php override
        if (is_readable('../config/test.module.config.php')) {
            $config['module_listener_options']['config_glob_paths'][] = '../config/test.module.config.php';
        }

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        static::$serviceManager = $serviceManager;
        static::$loaded         = true;
    }
}