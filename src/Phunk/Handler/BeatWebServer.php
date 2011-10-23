<?php

namespace Phunk\Handler;

class BeatWebServer extends BuiltinWebServer implements \Phunk\Handler
{
    function _get_command()
    {
        $php = PHP_BINDIR . DIRECTORY_SEPARATOR . 'php';
        if (isset($_SERVER['beat_bin'])) {
            $beat_bin = realpath($_SERVER['beat_bin']);
        } else {
            $beat_bin = `which beat.php`;
            $beat_bin = substr($beat_bin, 0, strlen($beat_bin) - 1);
        }
        if (!$beat_bin) {
            throw new \Exception("can not find beat.php.");
        }
        return "$php $beat_bin localhost 1985 {$this->_temporary_file}";
    }
}
