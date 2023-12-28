<?php

namespace Sajadsdi\LaravelSettingPro\Exceptions;

class DatabaseConnectionException extends \Exception
{
    public function __construct(string $database ,string $error)
    {
        parent::__construct("Failed to connect to $database database: \n" . $error);
    }
}
