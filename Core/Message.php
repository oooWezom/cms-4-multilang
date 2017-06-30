<?php
namespace Core;

use Core\QB\DB;

class Message
{

    static function GetMessage($type, $message, $time = 3500)
    {
        $message = addslashes($message);
        switch ($type) {
            case 1:
                $type = 'success';
                break;
            case 2:
                $type = 'danger';
                break;
            case 3:
                $type = 'info';
                break;
            default:
                $type = 'warning';
        }
        $_SESSION['GLOBAL_MESSAGE'] = '<script type="text/javascript">$(document).ready(function(){generate("' . $message . '", "' . $type . '", ' . (int)$time . ');});</script>';
    }

    static function GetMessage2($message, $time = 3500)
    {
        $_SESSION['GLOBAL_MESSAGE'] = '<script type="text/javascript">$(document).ready(function(){generate("' . $message . '", "info", ' . (int)$time . ');});</script>';
    }

}