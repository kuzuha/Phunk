<?php

namespace Phunk\Handler;

class Beat extends Simple implements \Phunk\Handler
{
    /**
     * @internal
     * @param int $status
     * @param array $headers
     */
    function _output_headers($status, array $headers)
    {
        print "HTTP/1.1 {$status} {$this->_status_code[$status]}\r\n";
        foreach ($headers as $key => $values) {
            if (false === is_array($values)) {
                print "$key: $values\r\n";
                continue;
            }
            foreach ($values as $value) {
                print "$key: $value\r\n";
            }
        }
        print "\r\n";
    }
}
