<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Peter Kokot 
 * @author  Dennis Degryse
 * @since   0.0.1
 * @version 0.0.5
 */

namespace PHPWorldWide\FacebookBot;

use PHPWorldWide\FacebookBot\Config\ConfigReader;

use PHPWorldWide\FacebookBot\Connection\ConnectionManager;

use PHPWorldWide\FacebookBot\Module\ModuleManager;

/**
 * The main class for the Facebook bot.
 */
class Bot
{
    /**
     * The connection manager.
     */
    private $connectionManager;

    /**
     * The module manager.
     */
    private $moduleManager;

    /**
     * The configuration reader.
     */
    private $config;

    /**
     * Constructor.
     *
     * @param ConfigReader $config The configuration reader.
     */
    public function __construct(ConfigReader $config)
    {
        $this->config = $config;
        $connectionParameters = $config->get('connection', 'PHPWorldWide\FacebookBot\Connection\ConnectionParameters');

        $this->connectionManager = new ConnectionManager($connectionParameters);
        $this->moduleManager = new ModuleManager($this->connectionManager);

        $this->autoloadModules();
    }

    /**
     * Gets the module manager.
     */
    public function getModuleManager()
    {
        return $this->moduleManager;
    }

    /**
     * Gets the connection manager.
     */
    public function getConnectionManager()
    {
        return $this->connectionManager;
    }

    /** 
     * Loads modules that are autoloaded.
     */
    private function autoloadModules() 
    {
        $moduleSections = array_filter(
            $this->config->getKeys(),
            function ($key) { return strpos($key, 'mod-') === 0; }
        );

        foreach ($moduleSections as $moduleSection) {
            $moduleName = substr($moduleSection, 4);
            $moduleConfig = $this->parseModuleConfig($moduleName);

            if ($moduleConfig->isAutoload()) {
                $this->moduleManager->loadModule($moduleName, $moduleConfig);
            }
        }
    }

    /**
     * Creates an instance of a module configuration object.
     *
     * @param string $moduleName The name of the module
     * @param array $config The arguments to supply with the module's constructor
     *
     * @return Module The new instance, which is an implementation of the Module interface.
     *
     * @throws ModuleException When the given module could not be loaded or when the representing 
     *                         class does not implement the Module interface.
     */
    private function parseModuleConfig($moduleName)
    {
        $parts = explode(".", $moduleName);
        $name = array_pop($parts);
        $parts += [$name . 'Module', $name . 'Config'];
        $fqClassName = ModuleManager::MOD_ROOTNS . '\\' . implode('\\', $parts);
        $configKey = 'mod-' . strtolower($name);

        return $this->config->get($configKey, $fqClassName);
    }
}
