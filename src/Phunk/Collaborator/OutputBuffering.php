<?php

namespace Phunk\Collaborator;

class OutputBuffering implements \Phunk\Collaborator
{
    /**
     * @static
     * @param callable $app
     * @param array $options
     * @return callable
     */
    static function collaborate(callable $app, array $options = array())
    {
        return function(array $env) use ($app)
        {
            ob_start();
            $res = $app($env);
            ob_end_clean();
            return $res;
        };
    }
}
