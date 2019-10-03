<?php

namespace Houdunwang\Module\Facade;

class HdModuleConfig extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'HDModule';
    }
}
