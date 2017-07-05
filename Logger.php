<?php
require_once '/vendor/autoload.php';

class Logger
{
    // fabric function for logger
    public static function getLogger ($logType)
    {
        // the log type is equal to the class name
        require_once($logType.'.php');
        if(class_exists($logType)) return new $logType;

        die('Cannot create new "'.$logType.'" class.');
    }
}
