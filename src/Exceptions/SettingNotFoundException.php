<?php

namespace Sajadsdi\LaravelSettingPro\Exceptions;

class SettingNotFoundException extends \Exception
{
    public function __construct($setting)
    {
        parent::__construct("We can't find '{$setting}' setting from store!");
    }
}
