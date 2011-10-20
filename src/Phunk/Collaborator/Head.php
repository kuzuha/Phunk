<?php

namespace Phunk\Collaborator;

class Head implements \Phunk\Collaborator
{
    /**
     * @static
     * @param callable $app
     * @param array $options
     * @return callable
     */
    static function collaborate(callable $app, array $options = array())
    {
        return function($env) use ($app)
        {
            $res = $app($env);
            $res[2] = '';
            return $res;
        };
    }
}
