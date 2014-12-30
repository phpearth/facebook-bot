<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Peter Kokot 
 * @author  Dennis Degryse
 * @since   0.0.5
 * @version 0.0.5
 */

namespace PHPWorldWide\FacebookBot\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * A configuration file reader.
 */
class ConfigReader
{
    /**
     * The name of the file where to read from.
     */ 
    private $fileName;

    /**
     * The content cached since the construction or since the last refresh.
     */
    private $contentCache;

    /**
     * Creates a new instance.
     *
     * @param string $fileName The name of the file where to read from.
     */
    public function __construct($fileName) 
    {
        $this->fileName = $fileName;
        $this->contentCache = self::parseYamlFile($fileName);
    }

    /**
     * Refreshes (a sub-structure) of the content.
     *
     * @param string $path The path to refresh.
     *
     * @throws Exception in case the path doesn't lead to a node.
     */
    public function refresh($path = null) 
    {
        $content = self::parseYamlFile($this->fileName);
        $node = self::getNode($path, $content);
        
        self::setNode($path, $this->contentCache, $node);
    }

    /**
     * Gets the value of a node.
     *
     * @param string $path The path to the node.
     * @param mixed $asObject Whether to return the value as an object, an instance or its initial
     *                        structure. True will return it as an object, false will return it as
     *                        its initial structure and a string will return it as the class
     *                        represented by the string.
     *
     * @throws Exception in case the path doesn't lead to a node.
     * @throws Exception in case something went wrong during the object or instance conversion.
     *
     * @return mixed The value in the requested structure.
     */
    public function get($path, $asObject = true)
    {
        $node = self::getNode($path, $this->contentCache);

        if ($asObject === true && is_array($node)) {
            $node = self::nodeToObject($node);
        } elseif (is_string($asObject)) {
            $node = self::nodeToInstance($node, $asObject);
        }

        return $node;
    }

    /**
     * Gets the keys in the node at the given path.
     *
     * @param string $path The path to the node.
     *
     * @throws Exception in case the path doesn't lead to a node.
     *
     * @return array The keys of the
     */
    public function getKeys($path = null) 
    {
        $node = self::getNode($path, $this->contentCache);

        return array_keys($node);
    }

    /**
     * Converts a node to a class instance. This is done by supplying the child node values at the
     * first level to the constructor of the class. This method assumes that optional parameters
     * are at the end of the parameter list.
     *
     * @param mixed $node The node to convert.
     * @param string $class The name of the class to convert to.
     *
     * @throws Exception In case a required parameter for the constructor is missing.
     * @throws Exception In case the class could not be found.
     *
     * @return object The class instance version of the node.
     */
    private static function nodeToInstance($node, $class)
    {
        try {
            $rflxClass = new \ReflectionClass($class);
            $rflxConstructor = $rflxClass->getConstructor();
            $rflxConstructorParameters = $rflxConstructor->getParameters();
            $args = [];
            $node = (array)self::nodeToObject($node, false);

            foreach ($rflxConstructorParameters as $rflxParameter) {
                $parameterName = $rflxParameter->getName();

                if (!array_key_exists($parameterName, $node)) {
                    if (!$rflxParameter->isOptional()) {
                        throw new \Exception("Parameter '$parameterName' is required in the constructor of '$class'.");
                    }

                    break;
                }

                $args[] = $node[$parameterName];
            }

            $instance = $rflxClass->newInstanceArgs($args);
        } catch (\ReflectionException $ex) {
            throw new \Exception("The class '$class' could not be found.");
        }

        return $instance;
    }

    /**
     * Converts a node to an object. Scalar nodes will not be converted.
     *
     * @param mixed $node The node to convert.
     * @param boolean $recursive Whether to convert nested array values.
     *
     * @return mixed The object version of the node or the node itself if it is scalar.
     */
    private static function nodeToObject($node, $recursive = true) 
    {
        if (is_array($node)) {
            $result = [];

            foreach ($node as $key => $value) {
                $property = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
                $result[$property] = $recursive ? self::nodeToObject($value) : $value;
            }

            $result = (object)$result;
        } else {
            $result = $node;
        }

        return $result;
    }

    /**
     * Gets the value of a node in a nested array.
     *
     * @param string $path The path to the node
     * @param array $array The array (by reference)
     *
     * @throws Exception in case the path doesn't exist in the array.
     */
    private static function getNode($path, &$array) 
    {
        $items = self::parseNodeList($path);
        $arrayPtr = &$array;

        foreach ($items as $item) {
            if (!array_key_exists($item, $arrayPtr)) {
                throw new \Exception("Invalid configuration path '$path'.");
            }

            $arrayPtr = &$arrayPtr[$item];
        }

        return $arrayPtr;
    }

    /**
     * Sets the value of a node in a nested array.
     *
     * @param string $path The path to the node
     * @param array $array The array (by reference)
     * @param mixed $value The value to set
     *
     * @throws Exception in case the path doesn't exist in the array.
     */
    private static function setNode($path, &$array, $value) 
    {
        $items = self::parseNodeList($path);

        if (count($items) === 0) {
            $array = $value;
        } else {
            $arrayPtr = &$array;
            $lastItem = array_pop($items);

            foreach ($items as $item) {
                if (!array_key_exists($item, $arrayPtr)) {
                    throw new \Exception("Invalid configuration path '$path'.");
                }

                $arrayPtr = &$arrayPtr[$item];
            }

            $arrayPtr[$lastItem] = $value;
        }
    }

    /**
     * Creates the node list from a given path. Each node in the path must be separated by a
     * period '.'.
     *
     * @param string $path The path to parse.
     *
     * @return array The list of nodes in the path.
     */
    private static function parseNodeList($path) 
    {
        return array_filter(
            explode('.', $path), 
            function($i) { return strlen($i) > 0; }
        );
    }

    /**
     * Parses the contents of a file (which is formatted in the YAML syntax) to an array.
     *
     * @param string $fileName The name of the file where to read from.
     *
     * @return array The parsed content.
     */
    private static function parseYamlFile($fileName)
    {
        return Yaml::parse(file_get_contents($fileName));
    }
}