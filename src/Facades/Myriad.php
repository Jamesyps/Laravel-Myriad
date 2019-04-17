<?php

namespace Jamesyps\Myriad\Facades;

use Illuminate\Support\Facades\Facade;

class Myriad extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Myriad';
    }
}
