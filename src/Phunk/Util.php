<?php

namespace Phunk;

use Phunk\Loader;

class Util
{
    /**
     * @param callable $app
     * @param array $collaborators
     * @return void
     */
    static function tune_up(callable &$app, array $collaborators)
    {
        foreach ($collaborators as $key => $collaborator) {
            $options = array();
            if (is_string($key)) {
                $options = $collaborator;
                $collaborator = $key;
            }
            $collaborator = self::_fix_class_name($collaborator, 'Phunk\\Collaborator');
            /* @var $collaborator \Phunk\Collaborator */
            $app = $collaborator::collaborate($app, $options);
        }
    }

    /**
     * @static
     * @param string $phunki
     * @return callable
     */
    static function load_phunki($phunki)
    {
        $app = null;
        require $phunki;
        if (null === $app) {
            $vars = get_defined_vars();
            $keys = array_keys($vars);
            $app = $vars[$keys[count($keys) - 1]];
        }
        return $app;
    }

    /**
     * @static
     * @param string|callable $phunki
     * @param array $options
     */
    static function phunk_up($phunki, array $options = array())
    {
        if (is_callable($phunki)) {
            $app = $phunki;
        } else {
            $app = self::load_phunki($phunki);
        }

        if (array_key_exists('handler', $options)) {
            Loader::load($options['handler'])->run($app);
        } else {
            Loader::auto()->run($app);
        }
    }

    /**
     * @internal
     * @static
     * @param string $class_name
     * @param string $namespace
     * @return string
     */
    static function _fix_class_name($class_name, $namespace)
    {
        if (0 === preg_match('/^\\\\?' . preg_quote($namespace, '/') . '\\\\/', $class_name)) {
            $class_name = $namespace . '\\' . $class_name;
        }
        return $class_name;
    }
}
