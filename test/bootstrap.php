<?php
set_include_path(
    __DIR__ . '/../src' . PATH_SEPARATOR .
        __DIR__ . '/../ext' . PATH_SEPARATOR .
        get_include_path());
require_once 'Autoloader/Simple.php';
spl_autoload_register(array('Autoloader_Simple', 'load'));

require_once 'PHPUnit/Framework/Assert/Functions.php';
