<?php

namespace Phunk\Handler;

class BuiltinWebServer implements \Phunk\Handler
{
    /**
     * @internal
     * @var string
     */
    public $_temporary_file;
    /**
     * @internal
     * @var resource
     */
    public $_process;
    /**
     * @internal
     * @var array
     */
    public $_pipes;

    function run(callable $app)
    {
        $this->_build_server_process();
        print "server started at http://localhost:1985/\n";
        print "when stop the server, use 'stop' command.\n";

        $write = array();
        $except = array();
        while (1) {
            if (false === $this->_check_process()) {
                fputs(STDERR, "server stopped.\n");
                break;
            }

            $read = array(STDIN, $this->_pipes[1], $this->_pipes[2]);
            $changed = stream_select($read, $write, $except, 0, 200000);
            if (false === $changed) {
                throw new \Exception();
            } elseif ($changed > 0) {
                foreach ($read as $r) {
                    if (STDIN === $r) {
                        $in = strtolower(trim(fread($r, 8192)));
                        if ('stop' === $in) {
                            break 2;
                        }
                    } elseif ($this->_pipes[1] === $r) {
                        print fread($r, 8192);
                    } else if ($this->_pipes[2] === $r) {
                        fputs(STDERR, fread($r, 8192));
                    }
                }
            }
        }
        $this->_finalize();
    }

    function _build_server_process()
    {
        $this->_build_temporary_file();

        if (array_key_exists('_', $_SERVER)) {
            $php = $_SERVER['_'];
        } else {
            $php = PHP_BINDIR . DIRECTORY_SEPARATOR . 'php';
        }

        $descriptor_spec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );

        $command = "$php -S=localhost:1985 {$this->_temporary_file}";
        $this->_process = proc_open($command, $descriptor_spec, $this->_pipes);
        if (false === $this->_process) {
            throw new \Exception("command failed: $command");
        }

        stream_set_blocking(STDIN, 0);
        stream_set_blocking($this->_pipes[1], 0);
        stream_set_blocking($this->_pipes[2], 0);
    }

    function _build_temporary_file()
    {
        global $argv;
        $temporary_dir = sys_get_temp_dir();
        $this->_temporary_file = tempnam($temporary_dir, 'phunk');
        $include_path = get_include_path();
        $phunki = realpath($argv[1]);
        $code = <<<CODE
<?php
set_include_path('$include_path');
require 'Autoloader/Simple.php';
spl_autoload_register(array('Autoloader_Simple', 'load'));
Phunk\Util::phunk_up('$phunki');
CODE;
        file_put_contents($this->_temporary_file, $code);
    }

    function _finalize()
    {
        if ($this->_process) {
            foreach ($this->_pipes as $pipe) {
                fclose($pipe);
            }
            proc_terminate($this->_process);
        }
        unlink($this->_temporary_file);
    }

    function _check_process()
    {
        $status = proc_get_status($this->_process);
        if ($status && $status['running']) {
            return true;
        }
        return $this->_process = false;
    }
}
