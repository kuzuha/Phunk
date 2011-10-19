<?php

namespace Phunk;

use Phunk\Loader;

class Util
{
    /**
     * @static
     * @param string $phunki
     * @return callable
     */
    static function load_phunki($phunki)
    {
        require $phunki;
        $vars = get_defined_vars();
        $keys = array_keys($vars);
        return $vars[$keys[count($keys) - 1]];
    }

    /**
     * @static
     * @param string $phunki
     * @param string $handler
     */
    static function phunk_up($phunki, $handler = null)
    {
        $app = self::load_phunki($phunki);
        if (null === $handler) {
            Loader::auto()->run($app);
        } else {
            Loader::load($handler)->run($app);
        }
    }
}
