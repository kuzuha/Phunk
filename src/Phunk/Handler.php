<?php

namespace Phunk;

interface Handler
{
    /**
     * @abstract
     * @param callable $app
     * @return void
     */
    function run(callable $app);
}
