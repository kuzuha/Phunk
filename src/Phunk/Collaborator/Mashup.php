<?php

namespace Phunk\Collaborator;

class Mashup implements \Phunk\Collaborator
{
    /**
     * @static
     * @param callable $app
     * @param array $options
     * @return callable
     */
    static function collaborate(callable $app, array $options = array())
    {
        foreach (static::$collaborators as $collaborator) {
            $collaborator = \Phunk\Util::_fix_class_name($collaborator, __NAMESPACE__);

            /* @var $collaborator \Phunk\Collaborator */
            $app = $collaborator::collaborate($app, $options);
        }
        return $app;
    }
}
