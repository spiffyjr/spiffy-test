<?php

namespace SpiffyTest;

use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\ServiceManager\ServiceManager;

class Module
{
    /**
     * @var Module
     */
    protected static $module = null;

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

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
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        $this->bootstrap();
        return $this->serviceManager;
    }

    /**
     * Initialize test.
     */
    public function bootstrap()
    {
        if ($this->loaded) {
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

        $this->initLoader(isset($config['loader_paths']) ? $config['loader_paths'] : array());

        $serviceManager = new ServiceManager(new ServiceManagerConfig());
        $serviceManager->setService('ApplicationConfig', $config);
        $serviceManager->get('ModuleManager')->loadModules();

        $this->serviceManager = $serviceManager;
        $this->loaded         = true;
    }

    public function initLoader(array $paths = array())
    {
        $vendor  = $this->getParentPath('vendor');
        $zf2Path = false;

        $loader = null;
        if (file_exists($vendor . '/autoload.php')) {
            $loader = include $vendor . '/autoload.php';
        }

        if (is_dir($vendor . '/ZF2/library')) {
            $zf2Path = $vendor . '/ZF2/library';
        } elseif (getenv('ZF2_PATH')) {
            $zf2Path = getenv('ZF2_PATH');
        } elseif (get_cfg_var('zf2_path')) {
            $zf2Path = get_cfg_var('zf2_path');
        }

        if ($zf2Path) {
            if (isset($loader)) {
                $loader->add('Zend', $zf2Path);
            } else {
                include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
                \Zend\Loader\AutoloaderFactory::factory(array(
                    'Zend\Loader\StandardAutoloader' => array(
                        'autoregister_zf' => true
                    )
                ));
            }
        }

        if ($loader) {
            foreach($paths as $name => $dir) {
                $loader->add($name, $dir);
            }
        }
    }

    protected function getParentPath($path)
    {
        $dir  = getcwd();
        $prev = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($prev === $dir) return false;
            $prev = $dir;
        }
        return $dir . '/' . $path;
    }
}
