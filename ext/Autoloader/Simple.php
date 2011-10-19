<?php

class Autoloader_Simple {

    /**
     * @var boolean
     */
    static $noWarnings = false;

    /**
     * @param string $className
     * @return boolean
     */
    static function load($className) {
        $filePath = self::getFilePath($className);
        self::$noWarnings ? @include $filePath : include $filePath;
        return class_exists($className) || interface_exists($className);
    }

    /**
     * @param string $className
     * @return string
     */
    static function getFilePath($className) {
        $exploded = explode('\\', $className);
        $last = array_pop($exploded);
        $exploded = array_merge($exploded, explode('_', $last));
        return implode(DIRECTORY_SEPARATOR, $exploded) . '.php';
    }

}