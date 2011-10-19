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
     * @param array $options
     */
    static function phunk_up($phunki, array $options = array())
    {
        $app = self::load_phunki($phunki);
        if (array_key_exists('handler', $options)) {
            Loader::load($options['handler'])->run($app);
        } else {
            Loader::auto()->run($app);
        }
    }
}
