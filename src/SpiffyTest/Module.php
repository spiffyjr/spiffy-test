<?php

namespace SpiffyTest;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\Application;
use Zend\ServiceManager\ServiceManager;

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

        if (is_readable('../config/test.application.config.php')) {
            // for backwards compatibility
            $config = include '../config/test.application.config.php';
        } else if (is_readable('./application.config.php')) {
            $config = include './application.config.php';
        } else {
            $config = include __DIR__ . '/../application.config.php.dist';
        }

        if (is_readable('../config/test.module.config.php')) {
            // for backwards compatibility
            $config = include '../config/test.module.config.php';
        } else if (is_readable('./module.config.php')) {
            $config['module_listener_options']['config_glob_paths'][] = './module.config.php';
        }

        $this->initLoader(isset($config['loader_paths']) ? $config['loader_paths'] : array());
        unset($config['loader_paths']);

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
