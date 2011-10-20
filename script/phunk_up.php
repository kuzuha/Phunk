<?php
require 'bootstrap.php';
array_shift($argv);
Phunk\Util::phunk_up(realpath($argv[0]));
