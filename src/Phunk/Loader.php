<?php

namespace Phunk;

class Loader
{
    /**
     * @static
     * @param array $args
     * @return Handler
     */
    static function auto(array $args = array())
    {
        switch (PHP_SAPI) {
            case 'cli':
                return self::load('BuiltinServer', $args);
            default:
                return self::load('Simple', $args);
        }
    }

    /**
     * @static
     * @param string $handler
     * @param array $args
     * @return Handler
     */
    static function load($handler, array $args = array())
    {
        $class = "Phunk\\Handler\\$handler";
        return new $class();
    }
}



