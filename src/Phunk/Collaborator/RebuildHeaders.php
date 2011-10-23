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
    static function _merge_headers()
    {
        $headers_array = func_get_args();
        $merged = array();
        foreach ($headers_array as $headers) {
            foreach ($headers as $key => $value) {
                if (is_int($key)) {
                    list($key, $value) = explode(':', $value, 2);
                }
                if (isset($headers[$key])) {
                    $merged[$key] = array($headers[$key], $value);
                } else {
                    $merged[$key] = $value;
                }
            }
        }
        return $merged;
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
            return array();
        }

        $headers = self::_merge_headers(headers_list());
        header_remove();
        return $headers;
    }

}
