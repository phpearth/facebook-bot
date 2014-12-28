<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.3
 * @version 0.0.3
 */

namespace PHPWorldWide\FacebookBot\Module;

use PHPWorldWide\FacebookBot\Connection\ConnectionManager;

/**
 * Provides a factory for modules.
 */
class ModuleManager
{
    const MOD_ROOTNS = 'PHPWorldWide\FacebookBot\Module';
    const MOD_INTERFACE = self::MOD_ROOTNS . '\Module';

    /**
     * The connection manager that can be used for creating new connections inside modules.
     */
    private $connectionManager;

    /**
     * The list of modules that have been loaded.
     */
    private $modules;

    /**
     * Creates a new instance.
     *
     * @param ConnectionManager $connectionManager The connection manager that can be used for
     *                                             creating new connections inside modules.
     */
    public function __construct(ConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
        $this->modules = [];
    }

    /**
     * Loads a module.
     *
     * @param string $moduleName The name of the module.
     * @param array $args The constructor arguments for the module.
     */
    public function loadModule($moduleName, $args = [])
    {
        if (array_key_exists($moduleName, $this->modules))
        {
            throw new ModuleException("A module with name '$modulename' was already registered.");
        }

        $module = $this->createModule($moduleName, $args);
        $module->start();

        $this->modules[$moduleName] = $module;
    }

    /**
     * Unloads a module.
     *
     * @param string $moduleName The name of the module.
     */
    public function unloadModule($moduleName)
    {
        if (array_key_exists($moduleName, $this->modules))
        {
            $module = $this->modules[$moduleName];
            $module->stop();

            unset($this->modules[$moduleName]);
        }
    }

    /**
     * Unloads all modules.
     */
    public function unloadAll()
    {
        $moduleNames = array_keys($this->modules);

        foreach ($moduleNames as $moduleName) {
            unloadModule($moduleName);
        }
    }

    /**
     * Creates an instance of a module.
     *
     * @param string $moduleName The name of the module
     * @param array $args The arguments to supply with the module's constructor
     *
     * @return Module The new instance, which is an implementation of the Module interface.
     *
     * @throws ModuleException When the given module could not be loaded or when the representing 
     *                         class does not implement the Module interface.
     */
    private function createModule($moduleName, $args) 
    {
        $parts = explode(".", $moduleName);
        $className = array_pop($parts) . 'Module';
        $parts += [$className, $className];
        $fqClassName = self::MOD_ROOTNS . '\\' . implode('\\', $parts);

        array_unshift($args, $this->connectionManager);

        try {
            $rflxClass = new \ReflectionClass($fqClassName);

            if (!in_array(self::MOD_INTERFACE, $rflxClass->getInterfaceNames())) {
                throw new ModuleException("Module '$moduleName' does not implement the Module interface.");
            }

            $instance = $rflxClass->newInstanceArgs($args);
        } catch (\ReflectionException $ex) {
            throw new ModuleException("A problem occured while loading module '$moduleName': " . $ex->getMessage());
        }

        return $instance;
    }
}