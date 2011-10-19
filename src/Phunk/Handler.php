<?php

namespace Phunk;

interface Handler
{
    function run(callable $app);
}
