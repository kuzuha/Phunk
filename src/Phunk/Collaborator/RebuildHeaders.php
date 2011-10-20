<?php

namespace Phunk\Collaborator;

class RebuildHeaders implements \Phunk\Collaborator
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
            self::_remove_headers();
            $res = $app($env);
            $res[1] = array_merge($res[1], self::_remove_headers());
            return $res;
        };
    }

    /**
     * @internal
     * @static
     * @return array
     */
    static function _remove_headers()
    {
        if (headers_sent($file, $line)) {
            trigger_error("headers already sent by $file:$line");
        } else {
            $headers = headers_list();
            foreach ($headers as $header) {
                list($header) = explode(':', $header);
                header_remove($header);
            }
            return $headers;
        }
    }

}
