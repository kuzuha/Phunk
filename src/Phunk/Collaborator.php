<?php

namespace Phunk;

interface Collaborator
{
    /**
     * @static
     * @abstract
     * @param callable $app
     * @param array $options
     * @return callable
     */
    static function collaborate(callable $app, array $options = array());
}
